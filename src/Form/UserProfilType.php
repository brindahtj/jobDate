<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserProfil;
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre prénom',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
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
            ->add('jobSought', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le poste recherché',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots',
                    'class' => 'form-control mb-2  shadow-sm',
                    'rows' => '5'
                ]
            ])
            ->add('availability', CheckboxType::class, [
                'label' => 'Etes-vous disponible ?',
                'attr' => ['class' => ' mb-2 ms-2 shadow-sm']
            ])
            ->add('website', UrlType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre site web',
                    'class' => 'form-control mb-2  shadow-sm'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => false,
                'required' => false,
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

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfil::class,
        ]);
    }
}
