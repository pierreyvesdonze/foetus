<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'pouet@pouet.pouet',
                ],
            ]
        );

        $builder->add(
            'phoneNumber',
            TextType::class,
            [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => '06 XX XX XX XX',
                ],
            ]
        );

        $builder
            ->add(
                'addressNumber',
                IntegerType::class,
                [
                    'label' => 'N° de la voie',
                    'attr' => [
                        'placeholder' => 'XXX'
                    ]
                ]
            )
            ->add(
                'addressStreet',
                TextType::class,
                [
                    'label' => 'Voie',
                    'attr' => [
                        'placeholder' => 'Rue du pâté pour chat'
                    ]
                ]
            )
            ->add(
                'addressPostal',
                IntegerType::class,
                [
                    'label' => 'Code postal',
                    'attr' => [
                        'placeholder' => 'XXXXX'
                    ]
                ]
            )
            ->add(
                'addressTown',
                TextType::class,
                [
                    'label' => 'Ville',
                    'attr' => [
                        'placeholder' => 'NYC'
                    ]
                ]
            );

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Enregistrer",
                'attr' => [
                    'class' => 'custom-button',
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
