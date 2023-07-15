<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccueilBisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startTime')
            ->add('duration')
            ->add('deadLine')
            ->add('placeMax')
            ->add('info')
            ->add('cancelReason')
            ->add('Image')
            ->add('place')
            ->add('state')
            ->add('site')
            ->add('promoter')
            ->add('users_events')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
