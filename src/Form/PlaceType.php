<?php

namespace App\Form;

use App\Entity\Place;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('place', EntityType::class, [
                'label' => 'Ville de rattachement : ',
                'class' => Place::class,
                'choice_label'=>'name',
                'placeholder' => '--Choisir un lieu--'
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue :'
            ])
            ->add('city', TextType::class,[
                'label' => 'Code postal : '
            ])
            ->add('latitude', IntegerType::class,[
                'label' => 'Latitude : '
            ])
            ->add('longitude', IntegerType::class,[
                'label' => 'longitude : '
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
