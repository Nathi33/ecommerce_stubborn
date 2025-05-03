<?php

namespace App\Form;

use App\Entity\Sweatshirt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la création ou modification d'un Sweatshirt.
 *
 * Ce formulaire permet de renseigner :
 * - le nom du sweatshirt,
 * - son prix,
 * - le stock disponible pour chaque taille (XS à XL),
 * - une image facultative,
 * - une option pour le mettre en avant.
 */
class SweatshirtType extends AbstractType
{
    /**
     * Construit le formulaire du Sweatshirt.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix ',
            ])
            ->add('stockXS', IntegerType::class, [
                'label' => 'Stock XS',
            ])
            ->add('stockS', IntegerType::class, [
                'label' => 'Stock S',
            ])
            ->add('stockM', IntegerType::class, [
                'label' => 'Stock M',
            ])
            ->add('stockL', IntegerType::class, [
                'label' => 'Stock L',
            ])
            ->add('stockXL', IntegerType::class, [
                'label' => 'Stock XL',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du sweatshirt',
                'mapped' => false,
                'required' => false,
            ])
            ->add('featured', CheckboxType::class, [
                'label' => 'Mettre en avant',
                'required' => false,
            ]);
    }

    /**
     * Configure les options du formulaire, notamment la classe de données associée.
     *
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sweatshirt::class,
        ]);
    }
}
