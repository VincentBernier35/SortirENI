<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AccueilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site', EntityType::class, ['label' => 'Site : ',
                                                        'class' => Site::class,
                                                        'required' => true,
                                                        'choice_label' => 'name',
                                                        'placeholder' => '--Choisir un lieu--'])
            ->add('promoter', CheckboxType::class, [
                'mapped' => false,
                'label' => "Sortie dont je suis l'organisateur/trice",
                'required' => false])
            ->add('registered', CheckboxType::class, [
                'mapped' => false,
                'label' => "Sortie auxquelles je suis inscrit/e",
                'required' => false])
            ->add('notRegistered', CheckboxType::class, [
                'mapped' => false,
                'label' => "Sortie auxquelles je ne suis pas inscrit/e",
                'required' => false])
            ->add('oldEvent', CheckboxType::class, [
                'mapped' => false,
                'label' => "Sorties passées",
                'required' => false])
            ->add('key',TextType::class,[
                'mapped' => false,
                'label'=>'Le nom de la sortie contient : ',
                'required' => false])
            ->add('startDateTime', DateType::class, [
                'mapped' => false,
                'required' => true,
                'widget' => 'single_text',
                'placeholder' => 'Choisir une valeure',
                'label'=>'Entre : ',
                'data' => new \DateTime('2023-01-01')
            ])
            ->add('endDateTime', DateType::class, [
                'mapped' => false,
                'required' => true,
                'widget' => 'single_text',
                'placeholder' => 'Choisir une valeure',
                'label'=> ' et ',
                'data' => new \DateTime('2024-01-01')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
