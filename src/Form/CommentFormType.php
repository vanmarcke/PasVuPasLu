<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class,[
                'label'=>"Votre commentaire : ",
                'attr' =>[
                    'class' => 'form-control placeholder_message',
                    'rows'=>6,
                    'placeholder' => "Saisissez votre commentaire"
                ],
                "constraints" => [
                    new Regex ([
                        "pattern" => "/[^<->()]$/",
                        "message" => "Caractère spécial interdit"
                    ])
                ]

            ])
            ->add('rate', ChoiceType::class,[
                'expanded' => true,  // radio or checkbox...
                'multiple' => false,
                'label' => false,
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
               ],
                'attr'=>[
                    'class' => 'rating__star',
                    'name'=> "star",
                    'id'=>"star_",
                    'label' => false,
                ]
            ])
            ->add('coverRate', ChoiceType::class,[
                'expanded' => true,  // radio or checkbox...
                'multiple' => false,
                'label' => false,
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
               ],
                'attr'=>[
                    'class' => 'rating__star',
                    'name'=> "star",
                    // 'id'=>"star_",
                    'label' => false,
                ]
            ])
            ;

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }


//    public function getBlockPrefix()
//    {
//        return 'App_comment';
//    }
}
