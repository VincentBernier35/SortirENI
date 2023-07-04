<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label'=>'Pseudo : '
            ])
            ->add('lastName', TextType::class, [
                'label'=>'Prénom : '
            ])
            ->add('firstName', TextType::class, [
                'label'=>'Nom : ',
                'required'=>false
            ])
            ->add('phoneNumber', TextType::class, [
                'label'=>'Téléphone : '
            ])
            ->add('email',EmailType::class, [
                'label'=>'Email : '
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de Pass : '],
                'second_options' => ['label' => 'Confirmation : '],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('site', ChoiceType::class, [
                'label' => 'Ville de rattachement : ',
                'class' => Site::class,
                'choice-lable'=>'name',
                'choices' => [
                    'Rennes' => 'Rennes',
                    'Brest' => 'Brest',
                    'Quimper' => 'Quimper',
                    'Lorient' => 'Lorient',
                    'Vannes' => 'Vannes',
                    'Chartes-de-Bretagne' => 'Chartes-de-Bretagne',
                    'Saint-Grégoire' => 'Saint-Grégoire',
                    'Vezin-le-Coquet' => 'Vezin-le-Coquet',
                    'Cession-Sévigné' => 'Cession-Sévigné',
                    'Bruz' => 'Bruz',
                ],
                'placeholder' => '--Choisir votre ville--'
            ])
            ->add('image', FileType::class,[
                'label' => 'Ma photo : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image(['maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
