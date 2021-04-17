<?php

namespace App\Form;

use App\Entity\Contact;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('to', ChoiceType::class, [
            'label'=>"A : ",
            'choices' => [
                'Auteurs' => 'auteur',
                'Lecteurs' => 'lecteur',
                'Tous' => 'tous'
            ],
        ])  
        ->add('objet', TextType::class, [
            'label'=>"Objet : ",
        ])
        ->add('contents', CKEditorType::class, [
            'label'=>"Contenu : ",
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}