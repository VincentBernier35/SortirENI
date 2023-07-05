<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'label' => 'Ville : ',
                'class' => City::class,
                'choice_label'=>'name',
                'placeholder' => '--Choisir une ville--'
            ])

            ->add('name', EntityType::class, [
                'label' => 'Ville de rattachement : ',
                'class' => Place::class,
                'choice_label'=>'name',
                'placeholder' => '--Choisir un lieu--'
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
