<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->setMethod("GET")
            ->add('mediaTitle' , TextType::class , [
                "label" => "Media title" ,
                "required" => false,
            ])
            ->add('userEmail' , TextType::class , [
                "label" => "User email",
                "required" => false,
            ])
            ->add('createdAt' , DateType::class , [
                "required" => false,
                "label" => "Created date :",
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
