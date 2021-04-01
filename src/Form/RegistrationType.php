<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => new  length(2, 2, 50),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => new  length(2, 2, 50),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Votre date de naissance',
                'widget' => 'single_text'
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre numéro de téléphone',
                'attr' => [
                'placeholder' => '0123456789'
    ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'constraints' => new  length(5, 5, 255),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse'
                ]
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'Code postal',
                'constraints' => new  length(5),
                'attr' => [
                    'placeholder' => '99999'
                ]
            ] )
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => new  length(3, 2, 100),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre ville'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Votre pseudo',
                'constraints' => new  length(2, 2, 50),
                'attr' => [
                    'placeholder' => 'merci de saisir votre pseudo'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => new  length(5, 5, 100),
                'attr' => [
                    'placeholder' => 'adresse@email.fr'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe doivent être identiques',
                'label' => 'Votre mot de passe',
                'required' => true,
                'constraints' => new  length(6, 5, 15),
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe'
                    ]
                ]
            ])
            ->add('cgv', null, [
                'label' => 'Accepter les CGV'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
