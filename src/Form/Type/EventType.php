<?php

namespace App\Form\Type;

use App\Entity\Event;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'actu",
                'required' => true,
                'empty_data' => 'Titre',
                'attr' => [
                    'class' => 'input-title'
                ]
            ])
            ->add('text', CKEditorType::class, [
                'required' => true,
                'empty_data' => 'Texte',
                'config' => ['uiColor' => '#ffffff']
            ])
            ->add('date', DateType::class, [
                'label' => "Date de l'actu (optionnel)",
                'attr' => [
                    'class' => 'input-date'
                ]
            ])
            ->add('image', FileType::class, [
                'label'    => "Lier une image à l'actu (optionnel)",
                'multiple' => false,
                'mapped'   => false,
                'required' => false,
                'attr'     => [
                    'class' => 'input-img'
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                    ])
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
            'data_class' => Event::class,
        ]);
    }
}
