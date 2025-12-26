<?php

namespace App\Form\Type;

use App\Enum\RoadType;
use App\Entity\TrophyRoad;
use App\Form\Type\TrophyFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrophyRoadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "Nom"
            ])

            ->add('type', EnumType::class, [
                'label' => 'Type de route',
                'class' => RoadType::class,
                'choice_label' => function (RoadType $type): string {
                    return match ($type) {
                        RoadType::MAIN => 'Principale',
                        RoadType::SECONDARY => 'Secondaire',
                    };
                },
                'required' => true,
            ])

            ->add('trophies', CollectionType::class, [
                'entry_type' => TrophyFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Trophées',
                'prototype' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrophyRoad::class,
        ]);
    }
}
