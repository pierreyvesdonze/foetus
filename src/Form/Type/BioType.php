<?php

namespace App\Form\Type;

use App\Entity\Bio;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('text', CKEditorType::class, [
                'config' => ['uiColor' => '#fffffff']
            ]);

        $builder->add('photoPath', FileType::class, [
            'label'    => 'Photo de la bio',
            'multiple' => false,
            'mapped'   => false,
            'required' => false,
            'attr'     => [
                'class' => 'img-bio'
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
