<?php

namespace App\Form;

use App\Entity\Players;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom')
        ->add('content')
        ->add('images',FileType::class,[
            'label'=>'photo',
            'multiple'=>false,
            'mapped'=>false
        ])
        ->add('poste')
        ->add('taille')
        ->add('adresseMail')
        ->add('ancienClub')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Players::class,
        ]);
    }
}
