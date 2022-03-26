<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Image extends Constraint
{
    public string $message = "validator.image.format";
    public string $mode = 'strict'; // If the constraint has configuration options, define them as public properties

    public function validatedBy()
    {
        return static::class.'Validator';
    }
}