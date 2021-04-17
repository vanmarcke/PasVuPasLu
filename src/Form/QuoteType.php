<?php

namespace App\Form;

use App\Entity\Quote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quote', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control-user form-control'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('auteur', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control-user form-control'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
