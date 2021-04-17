<?php

namespace App\Form;

use App\Entity\ContactTerhoma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;

class ContactTerhomaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => "Saisissez votre nom"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

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
                ]
            ])
            ->add('entreprise', TextType::class, [
                'attr' => [
                    'placeholder' => "Entreprise/Organisation"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]
            ])
            ->add('fonction', TextType::class, [
                'attr' => [
                    'placeholder' => "Fonction"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]
            ])
            ->add('telephone',TelType::class,[
                "label" => "Téléphone",
                "constraints" =>[
                    new NotBlank([ "message" => "Veuillez indiquer votre numero de téléphone"]),
                    new Regex([
                       "pattern" => "/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/",
                       "message" => "Le numero de téléphone n'est pas valide"
                    ])
                ]
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
            ->add('objet', TextareaType::class, [
                'attr' =>[
                    'class' => '',
                    'placeholder' => "Saisissez votre message"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ContactCaptcha',
                'label' => 'Retapez les caractères de l\'image'
              ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactTerhoma::class,
        ]);
    }
}
