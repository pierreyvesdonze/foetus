<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ]
        );

        $builder->add(
            'phone',
            TextType::class,
            [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                ],
            ]
        );

        $builder
            ->add('addressNumber', IntegerType::class, 
            [
                'label' => false,
                'attr' => [
                    'placeholder' => 'N° de la voie'
                ]
            ])
            ->add('addressStreet', TextType::class, 
            [
                'label '=> false,
                'attr' => [
                    'placeholder' => 'Voie'
                ]
            ])
            ->add('addressPostal', IntegerType::class,
            [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal'
                ]
            ])
            ->add('addressTown', TextType::class,
            [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ]);

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe'
                ]
            ],
            'second_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Confirmation du MDP'
                ]
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
