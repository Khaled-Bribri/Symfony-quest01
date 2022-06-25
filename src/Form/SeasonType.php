<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number',numberType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('year',numberType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('description',TextareaType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('program',null, ['choice_label' => 'title',
            'attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
