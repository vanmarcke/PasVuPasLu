<?php

namespace App\Form;

use App\Entity\Forum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre de votre discussion',
                'attr' => ['autocomplete' => 'off',
                    'placeholder' => 'Renseigner un titre pour votre discussion'
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Votre message ici ...',
                  'class' => 'col-sm-9 post-content post-message',
                   'id' => 'message'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Commencer une discussion',
                'attr' => ['class' => 'btn-default btn-forum',
                    'style' => 'color: white;']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
