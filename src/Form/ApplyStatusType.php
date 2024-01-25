<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ApplyStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, ['label' => false, 'choices' => ['Refusé' => 'STATUS_REFUSED', 'En cours' => 'STATUS_PENDING', 'Accepté' => 'STATUS_ACCEPTED'], 'attr' => ['placeholder' => 'Entrez votre statut', 'class' => 'form-control mb-2  shadow-sm']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
