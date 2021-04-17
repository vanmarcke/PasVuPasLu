<?php

namespace App\Form;

use App\Entity\Prices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Regex;

class PriceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'data_class' => null,
                'required'=>true,
                'label'=>"Titre",
                'attr'=>[
                    'placeholder'=>"Titre",
                    'class' => 'form-control col-md-12'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('title2',TextType::class, [
                'data_class' => null,
                'required'=>true,
                'label'=>"Sous-Titre",
                'attr'=>[
                    'placeholder'=>"Sous-Titre",
                    'class' => 'form-control col-md-12'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('text',CKEditorType::class, [
                'label'=>"Extrait du livre",'data_class' => null,
                'attr'=>[
                    'class' => 'col-md-12',
                    'name'=> "editor1"
                ]
            ])
            ->add('photo', FileType::class, [
                'label'=>'     ',
                'data_class' => null,
                'attr'=> [
                    'placeholder' => "Ajouter une photo de couverture"

                ]
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prices::class,
        ]);
    }
}
