<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire de demande de réinitialisation de mot de passe.
 * 
 * Ce formulaire permet à l'utilisateur de saisir son adresse email afin de recevoir
 * un lien de réinitialisation de mot de passe. Il contient un seul champ obligatoire.
 */
class ResetPasswordRequestFormType extends AbstractType
{
    /**
     * Construit le formulaire de demande de réinitialisation.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options passées au formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer votre adresse email',
                    ]),
                ],
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
