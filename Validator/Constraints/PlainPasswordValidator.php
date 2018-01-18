<?php

declare(strict_types=1);

namespace steevanb\UserBundle\Validator\Constraints;

use steevanb\UserBundle\Entity\AbstractUser;
use Symfony\Component\Validator\{
    Constraint,
    ConstraintValidator
};

class PlainPasswordValidator extends ConstraintValidator
{
    /**
     * @param AbstractUser $user
     * @param PlainPassword $constraint
     */
    public function validate($user, Constraint $constraint): void
    {
        if (
            $user->getPlainPassword() != null
            && $user->getPlainPasswordConfirmation() != null
            && $user->getPlainPassword() !== $user->getPlainPasswordConfirmation()
        ) {
            $this
                ->context
                ->buildViolation('user.plainPasswordConfirmation.different')
                ->atPath('plainPasswordConfirmation')
                ->addViolation();
        }
    }
}
