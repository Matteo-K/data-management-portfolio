<?php

namespace App\Form\Type;

use App\Entity\Technology;
use App\Entity\ProjectTechnology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProjectTechnologiesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("pourcentageUsing", NumberType::class, [
                "label" => "Pourcentage d'utilisation"
            ])
            ->add('technologie', EntityType::class, [
                'label' => 'Technologie',
                'class' => Technology::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectTechnology::class,
        ]);
    }
}
