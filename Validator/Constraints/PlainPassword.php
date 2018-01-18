<?php

declare(strict_types=1);

namespace steevanb\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PlainPassword extends Constraint
{
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
