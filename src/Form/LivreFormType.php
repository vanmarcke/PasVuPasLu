<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Livres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;

class LivreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class, [
                'data_class' => null,
                'required'=>true,
                'label'=>"Titre de votre livre",
                'attr'=>[
                    'placeholder'=>"Titre du livre",
                    'class' => 'form-control col-md-12'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('extract',CKEditorType::class, [
                'label'=>"Extrait du livre",
                'data_class' => null,
                'attr'=>[
                    'class' => 'col-md-12',
                    'name'=> "editor1"
                ]
            ])



            ->add('photo', FileType::class, [
                'label'=>'Photo : Type Portrait, Max 2 Mo',
                'data_class' => null,
                "constraints" => [
                    new File([                        
                        "maxSize" => "2048k" ,
                        "maxSizeMessage" => "le fichier ne doit pas faire plus de 2Mo",
    
                    ])
                    ],
                'attr'=> [
                    'placeholder' => "Ajouter une photo de couverture"

                ]
            ])

            ->add('categorie', EntityType::class,[
                'label'=>"Sélectionner une catégorie",'data_class' => null,
                'class'=>Categories::class,
                'choice_label'=>"categorie",
                'expanded'=>false,
                'multiple'=>false,
                'attr'=>[
                    'class' => 'form-control col-md-12'
                ]

            ])
    

            ->add('video', UrlType::class, [
                'label'=>'Vidéo : Url embed type : https://www.youtube.com/embed/nom_de_la_video / Exemple : https://www.youtube.com/embed/e8fx6U1Q2vg',
                'data_class' => null,
                'required'=>false,
                'attr'=> [
                    'placeholder' => "Lien de la vidéo Youtube (facultatif)"

                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
        ]);
    }
}
