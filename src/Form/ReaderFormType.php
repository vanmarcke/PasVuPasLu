<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\ProfilUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class ReaderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('pseudo',TextType::class,[
            "mapped" => false,
            "label" => "pseudo",
            'attr' => [
                'placeholder' => "Saisissez votre pseudo"
            ],
            "constraints" => [
                new Regex ([
                    "pattern" => "/[^<->()]$/",
                    "message" => "Caractère spécial interdit"
                ]),
                new NotBlank([ "message" => "Veuillez entrer le nom"]),
                new Length([
                "min" => 2,
                "minMessage" => "Le nom doit comporter au moins 2 caractères",
                "max" => 50,
                "maxMessage" => "Le nom ne doit pas dépasser 50 caractères"
                ])
                ],
            
        ])
        ->add('nom',TextType::class,[
            'attr' => [
                'placeholder' => "Saisissez votre nom"
            ],
            "constraints" => [
                new Regex ([
                    "pattern" => "/[^<->()]$/",
                    "message" => "Caractère spécial interdit"
                ]),
                new NotBlank([ "message" => "Veuillez entrer le nom"]),
                new Length([
                "min" => 3,
                "minMessage" => "Le nom doit comporter au moins 3 caractères",
                "max" => 50,
                "maxMessage" => "Le nom ne doit pas dépasser 50 caractères"
                ])
                ],
            
        ])
        ->add('prenom', TextType::class, [
            'attr' => [
                'placeholder' => "Saisissez votre prenom"
            ],
            "constraints" => [
                new Regex ([
                    "pattern" => "/[^<->()]$/",
                    "message" => "Caractère spécial interdit"
                ])
                ],

        ])
        ->add('birthDate', DateType::class, [
            "mapped" => false,
            "widget" => "single_text"
        ])
        ->add('email', EmailType::class, [
            'attr' => [
                'placeholder' => "Saisissez votre E-mail"
            ],
            "constraints" => [
                new Regex ([
                    "pattern" => "/[^<->()]$/",
                    "message" => "Caractère spécial interdit"
                ])
            ]

       ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe ne sont pas identiques.',
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmer mot de passe'],
            "constraints" => [
                new Regex([
                    "pattern" => "/^(?=.{4,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                    "message" => "Le mot de passe doit comporter au moins une minuscule, une majscule, un chiffre et un caractère spécial"
                ])
            ]
        ])
        ->add('adress', TextType::class,[
            "label" => "Adresse",
            "mapped" => false,
            'attr' => [
                'placeholder' => "Saisissez votre adresse"
            ],
            "constraints" => [
                new Regex ([
                    "pattern" => "/[^<->()]$/",
                    "message" => "Caractère spécial interdit"
                ]),
                new NotBlank([ "message" => "Veuillez entrer l'adresse de votre entreprise"]),
                new Length([
                "min" => 2,
                "minMessage" => "L'adresse doit comporter au moins 2 caractères",
                "max" => 50,
                "maxMessage" => "L'adresse ne doit pas dépasser 50 caractères"
                ])
            ]
        ])
        ->add('ville', TextType::class,[
            "mapped" => false,
            "label" => "Ville",
            'attr' => [
                'placeholder' => "Saisissez votre ville"
            ],
            "constraints" => [
                new Regex ([
                    "pattern" => "/[^<->()]$/",
                    "message" => "Caractère spécial interdit"
                ]),
                new NotBlank([ "message" => "Veuillez entrer votre municipalité"]),
                new Length([
                "min" => 2,
                "minMessage" => "Le nom de votre ville doit comporter au moins 2 caractères",
                ])
            ]
        ])
        ->add('codePostale',TextType::class,[
            "mapped" => false,
            "label" => "Code Postal",
            'attr' => [
                'placeholder' => "Saisissez votre code postal"
            ],
            "constraints" =>[
                new NotBlank([ "message" => "Veuillez indiquer votre code postal"]),
                new Regex([
                    "pattern" => "/^((0))?[0-9]{5}$/",
                    "message" => "Le code postal doit etre composer de 5 chiffres"
                ])
            ]
        ])
        ->add('photo', FileType::class, [
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
        
        ->add('country', CountryType::class,[
            "mapped" => false,
            "label" => "pays",
            'preferred_choices' => ['FR'],
            "constraints" => [
                new NotBlank([ "message" => "Veuillez entrer votre pays"]),
                new Length([
                "min" => 2,
                "minMessage" => "Le nom de votre ville doit comporter au moins 2 caractères",
                ])
            ]
        ])
        ->add('telephone',TelType::class,[
            "mapped" => false,
            "label" => "Téléphone",
            "constraints" =>[
                new NotBlank([ "message" => "Veuillez indiquer votre numero de téléphone"]),
                new Regex([
                   // "pattern" => "/^0[1-9][0-9]{8}$/",
                   "pattern" => "/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/",
                   // "pattern" => "/^(\+\d+(\s|-))?0\d(\s|-)?(\d{2}(\s|-)?){4}/",
                   "message" => "Le numero de téléphone n'est pas valide"
                ])
            ]
        ])
    ;
}
       
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}