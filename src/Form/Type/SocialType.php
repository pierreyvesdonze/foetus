<?php

namespace App\Form\Type;

use App\Entity\SocialLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du lien',
                'attr' => [
                    'class' => 'input-title',
                ],
            ]);

        $builder
            ->add('linkPath', TextType::class, [
                'label' => 'Lien vers le réseau social',
                'attr' => [
                    'class' => 'input-link'
                ]
            ]);

        $builder
            ->add('iconPath', FileType::class, [
                'label'    => 'Icône à charger',
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
            'data_class' => SocialLink::class,
        ]);
    }
}
