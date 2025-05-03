<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire permettant à un utilisateur de changer son mot de passe.
 * 
 * Ce formulaire utilise un champ de mot de passe répété pour forcer la confirmation du mot de passe.
 * Le mot de passe doit avoir une longueur minimale de 8 caractères et ne doit pas être vide.
 * Le champ n'est pas mappé à une propriété de l'entité (mapped: false), car l'encodage du mot de passe
 * est généralement géré manuellement dans le contrôleur.
 */
class ChangePasswordFormType extends AbstractType
{
    /**
     * Construit le formulaire de changement de mot de passe.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci d\'entrer votre mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                            // Longueur maximale imposée par Symfony pour des raisons de sécurité
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                ],
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                // Le champ n'est pas mappé à une propriété de l'entité User,
                // car le traitement est géré dans le contrôleur
                'mapped' => false,
            ])
        ;
    }

    /**
     * Configure les options par défaut du formulaire.
     *
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
