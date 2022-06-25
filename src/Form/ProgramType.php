<?php

namespace App\Form;
use App\Entity\Actor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('synopsis',TextareaType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('poster',TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('category',null, ['choice_label' => 'name',
            
                'attr' => ['class' => 'form-control']])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'firstname',
                'attr' => ['class' => 'form-control'],
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
