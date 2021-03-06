<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterType extends AbstractType
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
                'username', TextType::class, [
                'label' => 'register.username'
                ]
            )
            ->add(
                'email', EmailType::class, [
                'label'=> 'register.email'
                ]
            )
            ->add(
                'avatar', FileType::class, [
                    'label'=> 'register.avatar',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '2M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png'
                            ],
                            'mimeTypesMessage' => "validator.register.image.type",
                        ])
                    ],
                ]
            )
            ->add(
                'password', PasswordType::class, [
                'label'=>'register.password'
                ]
            )
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans( 'validator.confirm.password',[],'validators'),
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' =>$this->translator->trans( 'validator.password',[],'validators')],
                'second_options' => ['label' => $this->translator->trans( 'validator.repeat',[],'validators')],
            ])
            ->add(
                'submit', SubmitType::class, [
                'label' => 'register.submit',
                'attr' => [
                    'class' => 'submitUser',
                ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );

    }
}
