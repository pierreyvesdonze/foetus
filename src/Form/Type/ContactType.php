<?php

namespace App\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
            'required' => true,
            'label' => "Email",
            'attr' => [
                'class' => 'input-title',
                'placeholder' => "Merci d'utiliser une addresse mail valide si vous souhaitez une réponse"
            ]
            ])
            ->add('subject', TextType::class, [
                'empty_data' => 'Sujet',
                'label' => 'Sujet',
                'attr' => [
                    'class' => 'input-title',
                ],
            ])
            ->add('text', CKEditorType::class, [
                'empty_data' => 'Texte',
                'config' => ['uiColor' => '#fffffff']
            ]);

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Envoyer",
                'attr' => [
                    'class' => 'custom-button',
                ],
            ]
        );
    }
}
