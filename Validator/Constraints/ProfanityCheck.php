<?php

namespace Vangrg\ProfanityBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProfanityCheck extends Constraint
{
    public $message = 'The string contains an illegal word: {{ string }}.';
}
