<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'label'=> 'reset.confirm.username',
            ])
            ->add('password',PasswordType::class,[
                'label' => 'reset.password',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validator.confirm.password',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'reset.password'],
                'second_options' => ['label' => 'reset.confirm.password'],
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#-+!*$@%_])([#-+!*$@%_\w]{8,100})$/',
                        "validator.reset.password")
                ],
            ])
            ->add(
                'submit', SubmitType::class, [
                    'label' => 'reset.send',
                    'attr' => [
                        'class' => 'forgottenPasswordPlain'
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
