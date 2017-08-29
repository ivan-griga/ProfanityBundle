<?php

namespace Vangrg\ProfanityBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Vangrg\ProfanityBundle\Service\Check;

/**
 * {@inheritDoc}
 */
final class ProfanityCheckValidator extends ConstraintValidator
{
    /**
     * @var Check
     */
    private $profanityCheck;

    /**
     * ProfanityCheckValidator constructor.
     *
     * @param Check $profanityCheck
     */
    public function __construct(Check $profanityCheck)
    {
        $this->profanityCheck = $profanityCheck;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ProfanityCheck) {
            throw new UnexpectedTypeException($constraint, ProfanityCheck::class);
        }

        if (null === $value) {
            return;
        }

        $hasProfanity = $this->profanityCheck->hasProfanity($value);
        if ($hasProfanity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
