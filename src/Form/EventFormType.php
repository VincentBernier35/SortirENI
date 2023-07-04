<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('startTime', DateType::class,[
                'widget'=>'single_text',
                'input'=>'datetime_immutable'
            ])
            ->add('deadLine', DateType::class,[
                'widget'=>'single_text',
                'input'=>'datetime'
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
            ->add('place', TextType::class,[
                'label'=>'Ville organisatrice : ',
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
