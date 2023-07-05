<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AccueilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site', EntityType::class, ['label' => 'Site: ', 'class' => Site::class, 'required' => true, 'choice_label' => 'name'])
            ->add('orga', CheckboxType::class, [
                'mapped' => false,
                'label' => "Sortie dont je suis l'organisateur/trice",
                'required' => false]);

            /*->add('name')
            ->add('startTime')
            ->add('duration')
            ->add('deadLine')
            ->add('placeMax')
            ->add('info')
            ->add('place')
            ->add('state')
            ->add('site')
            ->add('promoter')
            ->add('users_events')
        ;*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
