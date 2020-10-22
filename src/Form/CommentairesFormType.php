<?php

namespace App\Form;

use App\Entity\Commentaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email',EmailType::class)
        ->add('pseudo',TextType::class)
        ->add('content',TextareaType::class,[
            'label'=>'Commentaire'
        ])
        ->add('rgpd',CheckboxType::class,[
            'label'=>"J'accepte que mes données soient enregistrées en base de données"
        ])
        ->add('poster',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
