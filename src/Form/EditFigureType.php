<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;

class EditFigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        /** @var Figure $data */
        $data = $builder->getData();

        $builder
            ->add(
                'name', TextType::class, [
                    'label' =>'editFigure.name',
                    'row_attr' => [
                        'class' => 'w-100'
                    ],
                ]
            )
            ->add(
                'category', EntityType::class, [
                    'label' => 'editFigure.category',
                    'class' => Category::class,
                    'row_attr' => [
                        'class' => 'w-100'
                    ],
                ]
            )
            ->add(
                'description', TextareaType::class, [
                    'label' => 'editFigure.description',
                    'row_attr' => [
                        'class' => 'w-100'
                    ],
                    'attr' => [
                        'class' => 'tinymce'
                    ]
                ]
            )
            ->add(
                'images', FileType::class, [
                    'label' => 'editFigure.images',
                    'mapped' => false,
                    'multiple' => true,
                    'required' => false,
                    'constraints' => [
                        new All([
                            new File([
                                "maxSize" => "5M",
                            ])
                        ])
                    ],
                    'row_attr' => [
                        'class' => 'w-100'
                    ]
                ]
            )
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'row_attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add(
                'submit', SubmitType::class, [
                    'label' => 'edit' === $options['mode'] ? 'editFigure.edit' : 'editFigure.add',
                    'attr' => [
                        'class' => 'submitUser mt-3',
                    ]
                ]
            );

            if ('edit' === $options['mode']) {
                $builder->add(
                    'delete', ButtonType::class, [
                        'label' => 'editFigure.delete',
                        'attr' => [
                            'class' => 'deleteFigure ml-2 mt-3',
                            'data-toggle'=>'modal',
                            'data-target' => '#delete'.$data->getId()
                        ]
                    ]
                );
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
            'mode' => 'add'
        ]);
    }
}
