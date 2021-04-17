<?php

namespace App\Form;

use App\Entity\ProfilUser;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ProfilUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',TextType::class, [
                'required'=>true,
                'label'=>"Pseudo / Prénom & Nom",
                'attr'=>[
                    'placeholder'=>"Saisissez un Pseudo ou re-saisir vos Prénom et Nom",
                    'class' => 'form-control col-md-12'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('presentation', TextareaType::class, [
                'label'=>"Présentation: ",
                'attr' =>[
                    'class' => 'form-control placeholder_message',
                    'rows'=>6,
                    'placeholder' => "Présentez-vous en quelques mots"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('age', TextType::class, [
                'required' => true,
                'label' => "Age",
                'attr' => [
                    'placeholder' => "Saisissez votre age"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ]),
                    new NotBlank([ "message" => "Veuillez entrer un age correct"]),
                    new Length([
                    "max" => 2,
                    "maxMessage" => "Vous ne pouvez pas avoir plus de 99 ans"
                    ])
                ]

            ])
            ->add('photo', FileType::class, [
                'label'=>"Choississez une Image",
                'data_class' => null,
                'attr'=> [
                    'class'=>'form-control col-md-12 form-control-file dropify'
                ]
            ])
            ->add('adress', TextType::class, [
                'required' => true,
                'label' => "Adresse",
                'attr' => [
                    'placeholder' => "Saisissez votre adresse"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('codePostale', TextType::class, [
                'required' => true,
                'label' => "Code postal",
                'attr' => [
                    'placeholder' => "Saisissez votre Code postal"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ]),
                    new NotBlank([ "message" => "Veuillez indiquer votre code postal"]),
                    new Regex([
                        "pattern" => "/^((0))?[0-9]{5}$/",
                        "message" => "Le code postal doit etre composer de 5 chiffres"
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'label' => "Ville",
                'attr' => [
                    'placeholder' => "Saisissez votre ville"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => "Pays",
                'attr' => [
                    'placeholder' => "Saisissez votre pays"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('telephone', TextType::class, [
                    'required' => true,
                    'label' => "Téléphone",
                    'attr' => [
                        'placeholder' => "Saisissez votre numéro de télèphone"
                    ],
                    "constraints" => [
                        new Regex ([
                            "pattern" => "/^0[1-9][0-9]{8}$/",
                            "message" => "Le numéro doit comporter 10 chiffres"
                        ])
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfilUser::class,
        ]);
    }
}
