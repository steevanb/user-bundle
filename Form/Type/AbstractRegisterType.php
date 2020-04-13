<?php

declare(strict_types=1);

namespace steevanb\UserBundle\Form\Type;

use steevanb\SymfonyFormOptionsBuilder\{
    FormOptionsBuilder\RootFormOptionsBuilder,
    OptionsBuilder\EmailOptionsBuilder,
    OptionsBuilder\PasswordOptionsBuilder,
    OptionsBuilder\TextOptionsBuilder
};
use Symfony\Component\Form\{
    AbstractType,
    FormBuilderInterface
};
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRegisterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            RootFormOptionsBuilder::create()
                ->setValidationGroups(['register'])
                ->setTranslationDomain('register')
                ->asArray()
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(...$this->createUserNameOptionsBuilder()->asVariadic('username'));
        $builder->add(...$this->createPlainPasswordOptionsBuilder()->asVariadic('plainPassword'));
        $builder->add(
            ...$this->createPlainPasswordConfirmationOptionsBuilder()->asVariadic('plainPasswordConfirmation')
        );
        $builder->add(...$this->createEmailOptionsBuilder()->asVariadic('email'));
    }

    protected function createUserNameOptionsBuilder(): TextOptionsBuilder
    {
        return TextOptionsBuilder::create()
            ->setLabel('user.username');
    }

    protected function createPlainPasswordOptionsBuilder(): PasswordOptionsBuilder
    {
        return PasswordOptionsBuilder::create()
            ->setLabel('user.plainPassword');
    }

    protected function createPlainPasswordConfirmationOptionsBuilder(): PasswordOptionsBuilder
    {
        return PasswordOptionsBuilder::create()
            ->setLabel('user.plainPasswordConfirmation');
    }

    protected function createEmailOptionsBuilder(): EmailOptionsBuilder
    {
        return EmailOptionsBuilder::create()
            ->setLabel('user.email');
    }
}
