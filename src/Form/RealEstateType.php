<?php

namespace App\Form;

use App\Entity\RealEstate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RealEstateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface', RangeType::class, [
                // On configure un <input type="range" min="10" max="400">
                'attr' => [
                    'min' => 10,
                    'max' => 400,
                    'class' => 'p-0', // Ajoute une classe sur le input
                ],
            ])
            ->add('price', null, [
                // On peut définir le label directement dans le PHP
                'label' => 'Prix',
            ])
            // On peut changer le type du champ avec le second paramètre
            ->add('rooms', ChoiceType::class, [
                // Attention, <option value="VALUE">KEY</option>
                'choices' => [
                    'Studio' => 1,
                    'T2' => 2,
                    'T3' => 3,
                    'T4' => 4,
                    'T5' => 5,
                ],
                'label' => 'Nombre de pièces',
            ])
            ->add('type', null, [
                'choice_label' => 'name', // On va afficher la valeur de la propriété name des objets Type
                'expanded' => true, // On mets des input radio
            ])
            ->add('sold', ChoiceType::class, [
                'label' => 'Vendu ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                // 'expanded' => true,
            ])
            ->add('image', FileType::class, [
                // On désactive le lien avec la BDD pour l'image car Symfony ne sait pas l'afficher
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // RealEstate::class équivaut à 'App\Entity\RealEstate'
            // Fais le lien entre le formulaire et l'entité
            'data_class' => RealEstate::class,
        ]);
    }
}
