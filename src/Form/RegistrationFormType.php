<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,  ['label' => false, 'attr' => ['placeholder' => 'Entrez votre email', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('username', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Entrez votre nom', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('status', ChoiceType::class, ['label' => false, 'choices' => ['Candidat' => 'Candidat', 'Professionnel' => 'Professionnel'], 'attr' => ['placeholder' => 'Entrez votre statut', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => ['class' => ' mb-2 ms-2 shadow-sm'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Entrez votre mot de passe', 'class' => 'form-control mb-2  shadow-sm'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],

            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'Entrez votre mot de passe', 'class' => 'form-control mb-2  shadow-sm']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Confirmer votre mot de passe', 'class' => 'form-control mb-2  shadow-sm']],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contnir au moins {{limit}} caratères.',
                        'max' => 4096
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
