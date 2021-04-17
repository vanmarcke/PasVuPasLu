<?php

namespace App\Form;

use App\Entity\Dasboard;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class DasboardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quate_auter', CKEditorType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control-user form-control'
                ]
            ])
            ->add('quate_insp', TextType::class, [
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
            ->add('content_editable', CKEditorType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control-user form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dasboard::class,
        ]);
    }
}
