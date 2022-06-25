<?php

namespace App\Form;

use App\Entity\Actor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TexteType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('lastname',TexteType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('Birthdate', DateType::class, [
                'widget' => 'single_text',
            
                'attr' => ['class' => 'form-control'],
            ])
            ->add('programs',null,['choice_label' => 'title', 'multiple' => true, 'expanded' => true, 'by_reference' => false,'placeholder' => 'Choose an option'],
            ['label' => 'Programs']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actor::class,
        ]);
    }
}
