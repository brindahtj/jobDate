<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserEntreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre prénom',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre ville',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre code postal',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'preferred_choices' => [
                    'FR',
                    'BE',
                    'CH',
                    'LU',
                ],
                'attr' => [
                    'class' => 'form-control mb-2  shadow-sm'
                ]

            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('activityArea', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le secteur d\'activité',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Présentez l\'entreprise en quelques mots',
                    'class' => 'form-control mb-2  shadow-sm',
                    'rows' => '5'
                ]
            ])
            ->add('logoEntreprise', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image  de type jpg, jpeg, png ou webp',
                    ])
                ],

            ])
            ->add('website', UrlType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre site web',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserEntreprise::class,
        ]);
    }
}
