<?php

declare(strict_types=1);

namespace steevanb\UserBundle\Form\Type;

use steevanb\SymfonyFormOptionsBuilder\{
    OptionsBuilder\PasswordOptionsBuilder,
    OptionsBuilder\TextOptionsBuilder
};
use Symfony\Component\Form\{
    AbstractType,
    FormBuilderInterface
};

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(...TextOptionsBuilder::create()->asVariadic('username'));

        $builder->add(...PasswordOptionsBuilder::create()->asVariadic('password'));
    }
}
