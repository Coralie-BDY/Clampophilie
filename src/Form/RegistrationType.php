<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Votre date de naissance',
                'format' => 'dd-MM-yyyy'
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre numéro de téléphone',
                'attr' => [
                'placeholder' => '0123456789'
    ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse'
                ]
            ])
            ->add('postalCode', NumberType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => '99999'
                ]
            ] )
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre ville'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Votre pseudo',
                'attr' => [
                    'placeholder' => 'merci de saisir votre pseudo'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => [
                    'placeholder' => 'adresse@email.fr'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Votre mot de passe',
                'attr' => [
                    'placeholder' => 'merci de saisir votre mot de passe'
                ]
            ])
            ->add('password_confirm', PasswordType::class, [
                'label' => 'Confirmer votre mot de passe',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'merci de confirmer votre mot de passe'
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
