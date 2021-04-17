<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Regex;

class NewsType extends AbstractType
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
            ->add('subtitle',CKEditorType::class, [
                'data_class' => null,
                'required'=>true,
                'label'=>"Sous-titre",
                'attr'=>[
                    'placeholder'=>"Sous-titre",
                    'class' => 'form-control col-md-12'
                ]
            ])
            ->add('text',CKEditorType::class, [
                'label'=>"Description",
                'data_class' => null,
                'attr'=>[
                    'class' => 'col-md-12',
                    'name'=> "editor1"
                ]
            ])
            ->add('photo', FileType::class, [
                'label'=>'     ',
                'data_class' => null,
                'attr'=> [
                    'placeholder' => "Ajouter une photo"

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
