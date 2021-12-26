<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddFigureType extends AbstractType
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name', TextType::class, [
                'label' => $this->translator->trans('editFigure.name'),
                'row_attr' => [
                    'class' => 'col-md-6'
                ],
                ]
            )
            ->add(
                'category', EntityType::class, [
                'label' => $this->translator->trans('editFigure.category'),
                    'placeholder' => 'Sélectionnez une catégorie',
                'class' => Category::class,
                'row_attr' => [
                    'class' => 'col-md-6'
                ],
                ]
            )
            ->add(
                'description', TextareaType::class, [
                'label' => $this->translator->trans('editFigure.description'),
                'row_attr' => [
                    'class' => 'col-md-12'
                ],
                    'attr' => [
                        'class' => 'tinymce'
                    ]
                ]
            )
            ->add(
                'images', FileType::class, [
                'label' => $this->translator->trans('editFigure.images'),
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'row_attr' => [
                    'class' => 'col-md-6'
                ]
                ]
            )
            ->add(
                'videos', FileType::class, [
                'label' => $this->translator->trans('editFigure.videos'),
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'row_attr' => [
                    'class' => 'col-md-6'
                ]
                ]
            )
            ->add(
                'submit', SubmitType::class, [
                'label' => (isset($options['attr']['type'])) ? $this->translator->trans('editFigure.edit') : $this->translator->trans('editFigure.add'),
                'attr' => [
                    'class' => 'submitUser mt-3',

                ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => Figure::class,
            ]
        );
    }
}
