<?php

namespace App\Form;


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

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie : '
            ])
            ->add('startTime', DateTimeType::class,[
                'label'=>'Date et heure de la sortie : ',
                'widget'=>'single_text',
                'input'=>'datetime'
            ])
            ->add('deadLine', DateType::class,[
                'label'=>'Date limite d\'inscription : ',
                'widget'=>'single_text',
                'input'=>'datetime',
            ])
            ->add('placeMax', IntegerType::class,[
                'label'=>'Nombre de place : '
            ])
            ->add('duration', IntegerType::class,[
                'label'=>'Durée : '
            ])
            ->add('info', TextareaType::class,[
                'label'=>'Description et infos : '
            ])
            ->add('place', EntityType::class,[
                'class'=> Place::class,
                'label'=>'Lieu : ',
                'choice_label' => 'name',
                'placeholder' => '--Choisir un lieu--'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
