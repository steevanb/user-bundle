<?php

declare(strict_types=1);

namespace steevanb\UserBundle\Controller;

use steevanb\UserBundle\{
    Entity\AbstractUser,
    Form\Type\LoginType
};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Component\Security\{
    Core\Authentication\Token\UsernamePasswordToken,
    Core\User\UserInterface,
    Http\Event\InteractiveLoginEvent
};

abstract class AbstractSecurityController extends Controller
{
    abstract protected function createUser(): AbstractUser;

    abstract protected function createRegisterForm(): FormInterface;

    abstract protected function createRegisteredResponse(): Response;

    public function register(Request $request): Response
    {
        $form = $this->createRegisterForm();
        $return = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this
                ->defineRegisteredUserRoles($form->getData())
                ->defineRegisteredUserEncodedPassword($form->getData());

            $manager = $this->container->get('doctrine')->getManager();
            $manager->persist($form->getData());
            $manager->flush();

            if ($this->isAutoLoginAfterRegistration()) {
                $this->loginUser($form->getData(), $request);
            }
            $return = $this->createRegisteredResponse();
        } else {
            $return = $this->render(
                $this->getRegisterTemplateName(),
                $this->getRegisterTemplateParameters($form)
            );
        }

        return $return;
    }

    public function login(): Response
    {
        return $this->render($this->getLoginTemplateName(), $this->getLoginTemplateParameters());
    }

    protected function getRegisterTemplateName(): string
    {
        return 'Security/register.html.twig';
    }

    protected function getRegisterTemplateParameters(FormInterface $form): array
    {
        return [
            'formView' => $form->createView(),
            'errors' => $form->getErrors(true)
        ];
    }

    protected function defineRegisteredUserRoles(AbstractUser $user): self
    {
        $user->setRoles(['ROLE_USER']);

        return $this;
    }

    protected function defineRegisteredUserEncodedPassword(AbstractUser $user): self
    {
        $user
            ->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword()))
            ->setPlainPassword(null);

        return $this;
    }

    protected function createLoginForm(): FormInterface
    {
        $user = $this->createUser();
        $user->setUsername($this->container->get('security.authentication_utils')->getLastUsername());

        return $this->createForm($this->getLoginType(), $user);
    }

    protected function getLoginType(): string
    {
        return LoginType::class;
    }

    protected function getLoginTemplateName(): string
    {
        return 'Security/login.html.twig';
    }

    protected function getLoginTemplateParameters(): array
    {
        return [
            'formView' => $this->createLoginForm()->createView(),
            'error' => $this->get('security.authentication_utils')->getLastAuthenticationError() instanceof \Exception
                ? $this->get('translator')->trans('connexion.error', [], 'security')
                : null
        ];
    }

    protected function isAutoLoginAfterRegistration(): bool
    {
        return true;
    }

    protected function loginUser(UserInterface $user, Request $request): self
    {
        $token = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'main',
            $user->getRoles()
        );
        $this->container->get('security.token_storage')->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $this->container->get('event_dispatcher')->dispatch('security.interactive_login', $event);

        // http://symfony.com/doc/current/testing/http_authentication.html
        $session = $this->container->get('session');
        $session->set('_security_main', serialize($token));
        $session->save();

        $request->cookies->set($session->getName(), $session->getId());

        return $this;
    }
}
