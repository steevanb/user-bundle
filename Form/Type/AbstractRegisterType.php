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
                ->asArray()
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(...TextOptionsBuilder::create()->asVariadic('username'));

        $builder->add(...PasswordOptionsBuilder::create()->asVariadic('plainPassword'));

        $builder->add(...PasswordOptionsBuilder::create()->asVariadic('plainPasswordConfirmation'));

        $builder->add(...EmailOptionsBuilder::create()->asVariadic('email'));
    }
}
