<?php

namespace App\Form;

use App\Entity\Sweatshirt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SweatshirtType extends AbstractType
{
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sweatshirt::class,
        ]);
    }
}
