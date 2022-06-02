<?php

namespace App\Form;

use App\Entity\Actor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('birth_date', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Date de naissance',
                ],
            ])
          
            ->add('programs',null, ['choice_label' => 'title'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actor::class,
        ]);
    }
}
