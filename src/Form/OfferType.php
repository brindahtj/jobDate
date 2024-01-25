<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Offer;
use App\Entity\ContractType;
use App\Entity\UserEntreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Entrez le titre de l\'offre', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('shortDescription', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Entrez le titre de la description', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('content', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => 'Entrez la description', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('salary', MoneyType::class, ['label' => false, 'attr' => ['placeholder' => 'Entrez le salaire', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('location', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Entrez le lieu', 'class' => 'form-control mb-2  shadow-sm']])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-select']
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
