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
}
