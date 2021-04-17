<?php

namespace App\Form;

use App\Entity\Podcast;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class PodcastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('titre', TextType::class, [
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('description', CKEditorType::class)
            ->add('audio', FileType::class, [ 
                "mapped" => false,
                "required" => false,
                "data_class" => null,
                "constraints" => [
                    new File([
                        "mimeTypes" => [ "audio/mpeg", "audio/ogg"] ,
                        "maxSize" => "10000k" ,
                        "maxSizeMessage" => "le fichier ne doit pas faire plus de 10Mo",
                        ])
                ]
            ])
            ->add('link', TextType::class, [
                "required" => false,
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('image', FileType::class, [
                "mapped" => false,
                "required" => false,
                "data_class" => null,
                "constraints" => [
                    new File([
                        "mimeTypes" => [ "image/gif", "image/jpeg", "image/png" ] ,
                        "maxSize" => "2048k" ,
                        "maxSizeMessage" => "le fichier ne doit pas faire plus de 2Mo",
    
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Podcast::class,
        ]);
    }
}
