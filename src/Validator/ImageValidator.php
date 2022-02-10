<?php

namespace App\Validator;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ImageValidator extends ConstraintValidator
{

    const MIME_TYPE = [
        'image/png',
        'image/jpg',
        'image/jpeg'
    ];

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Image) {
            throw new UnexpectedTypeException($constraint, Image::class);
        }

        // access your configuration options like this:
        if ('strict' === $constraint->mode) {
            // ...
        }

        if ($value->getSize() > 5242880) {
            $this->context->buildViolation('validator.image.size')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

        /** @var UploadedFile $value */
        if (!in_array($value->getMimeType(),ImageValidator::MIME_TYPE)) {
            $this->context->buildViolation('validator.image.mimetype')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

    }
}