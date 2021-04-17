<?php

namespace App\Form;

use App\Entity\CouponLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class CouponLivreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coupon', TextType::class, [
                'attr' => [
                  'class' => 'form-control form-control-user',
                  'placeholder' => "Nom du code promo",
                  'autocomplete' => 'off'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])

            ->add('expirdate_at', DateType::class, [
                'widget' => 'single_text',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                // adds a class that can be selected in JavaScript
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker form-control form-control-user',
                    'placeholder' => 'Date format dd/mm/yyyy',
                    'autocomplete' => 'off'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CouponLivre::class,
        ]);
    }
}
