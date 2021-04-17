<?php

namespace App\Form;

use App\Entity\ForumReply;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['class' => 'col-sm-9 post-content post-message',
                    'placeholder' => 'votre message ici ...']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Répondre à la discussion',
                'attr' => ['class' => 'btn-default btn-forum',
                    'style' => 'color: white;']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumReply::class,
        ]);
    }
}
