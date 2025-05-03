<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Formulaire d'inscription d'un nouvel utilisateur.
 * 
 * Ce formulaire permet de collecter les informations nécessaires à la création d'un compte utilisateur :
 * email, nom d'utilisateur, adresse de livraison et mot de passe (avec confirmation).
 * Les mots de passe ne sont pas mappés à l'entité User afin d'être encodés dans le contrôleur.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Construit le formulaire d'inscription.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options passées au formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new NotBlank(['message' => 'Merci d\'entrer un nom d\'utilisateur']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom d\'utilisateur doit faire au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('deliveryAddress', TextType::class, [
                'label' => 'Adresse de livraison',
                'constraints' => [
                    new NotBlank(['message' => 'Merci d\'entrer une adresse de livraison']),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // longueur maximale autorisée par Symfony pour des raisons de sécurité
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('plainPasswordConfirm', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Confirmer le mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de confirmer votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    /**
     * Configure les options par défaut du formulaire, notamment la classe de données liée.
     *
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
