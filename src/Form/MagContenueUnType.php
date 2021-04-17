<?php

namespace App\Form;

use App\Entity\MagContents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class MagContenueUnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('TextMag', TextType::class, [
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
            'data_class' => MagContents::class,
        ]);
    }
}