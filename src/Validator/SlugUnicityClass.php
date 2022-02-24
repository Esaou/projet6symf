<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SlugUnicityClass extends Constraint
{
    public $message = 'validator.slug';
    public $mode = 'strict'; // If the constraint has configuration options, define them as public properties

    public function validatedBy()
    {
        return static::class.'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}