<?php

namespace App\Form;


use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class,[
                'class'=> City::class,
                'label'=>'Ville : ',
                'choice_label' => 'name',
                'placeholder' => '--Choisir un lieu--'
            ])
            ->add('name', TextType::class,[
                'label'=>'Nom du lieu :',
            ])
            ->add('street', TextType::class,[
                'label'=>'Rue :',
            ])
            ->add('latitude', TextType::class,[
                'label'=>'Latitude : '
            ])
            ->add('longitude', TextType::class,[
                'label'=>'Longitude : '
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
