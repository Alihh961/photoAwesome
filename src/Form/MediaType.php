<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filePath')
            ->add('title')
            ->add('description')
            ->add('categories', EntityType::class, [
                "class" => Category::class,
                "choice_label" => "label",
                'multiple' => true,
                'expanded' => true,

            ])
//            ->add("user",EntityType::class , [
//                "class" => User::class ,
//                "query_builder"=> function(EntityRepository $entityRepository) { // this function is to display emails in the order A-Z
//                    return $entityRepository->createQueryBuilder("userTable")
//                        ->orderBy("userTable.email", "ASC");
//                },
//                "choice_label"=>"email",
//                "expanded"=>true,
//
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
