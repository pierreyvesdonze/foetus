<?php

namespace App\Form;

use App\Entity\Bio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la bio',
                'attr' => [
                    'class' => 'input-title',
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Texte de la bio',
                'attr' => [
                    'class' => 'textarea',
                ],
            ]);

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Enregistrer",
                'attr' => [
                    'class' => 'button',
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bio::class,
        ]);
    }
}
