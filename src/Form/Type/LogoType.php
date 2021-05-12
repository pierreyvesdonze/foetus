<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class LogoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {            
        $builder->add('logo', FileType::class, [
            'label'    => 'Ajouter un logo',
            'multiple' => false,
            'mapped'   => false,
            'required' => false,
            'attr'     => [
                'class' => 'home-img'
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
}
