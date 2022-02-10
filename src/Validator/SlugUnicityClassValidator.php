<?php

namespace App\Validator;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SlugUnicityClassValidator extends ConstraintValidator
{
    private FigureRepository $figureRepository;

    private SluggerInterface $slugger;

    public function __construct(FigureRepository $figureRepository,SluggerInterface $slugger)
    {
        $this->figureRepository = $figureRepository;
        $this->slugger = $slugger;

    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof SlugUnicityClass) {
            throw new UnexpectedTypeException($constraint, SlugUnicityClass::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        // access your configuration options like this:
        if ('strict' === $constraint->mode) {
            // ...
        }

        /** @var Figure $value */
        $figureExist = $this->figureRepository->getFigureBySlug($value);

        dd($figureExist,$value);

        if ($figureExist->getId() === $value->getId()) {

        }

        if (null !== $figureExist) {

                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->atPath('name')
                    ->addViolation();

        }
    }
}