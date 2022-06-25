<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('number',NumberType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('synopsis',TextareaType::class, [
                'attr' => ['class' => 'form-control',
                ]
            ])
            ->add('season',null, ['choice_label' => 'number',
            'attr' => ['class' => 'form-control']])
            ->add('program',null, ['choice_label' => 'title',
            'attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
