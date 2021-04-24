<?php

namespace App\Form\Type;

use App\Entity\Rate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du tarif',
                'attr' => [
                    'class' => 'input-title',
                    'placeholder' => 'ex. : Tatouage'
                ],
            ]);

        $builder
            ->add('amount', TextType::class, [
                'label' => 'Montant',
                'attr' => [
                    'placeholder' => "ex. : 100€ de l'heure"
                ]
            ]);


        $builder
            ->add('text', TextType::class, [
                'label' => 'Texte explicatif',
                'required' => false,
                'attr' => [
                    'class' => 'input-title',
                    'placeholder' => 'ex. : Je suis sympa je ne fais pas mal...'
                ],
            ]);

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
            'data_class' => Rate::class,
        ]);
    }
}
