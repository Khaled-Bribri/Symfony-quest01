<?php

namespace App\Form;
use App\Entity\Actor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('synopsis')
            ->add('poster')
            ->add('category',null, ['choice_label' => 'name'])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'firstname',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ]);
        }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }


  



}
