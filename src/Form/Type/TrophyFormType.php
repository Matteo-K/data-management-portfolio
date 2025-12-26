<?php

namespace App\Form\Type;

use App\Entity\Trophy;
use App\Enum\TrophyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrophyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'Type de trophée',
                'class' => TrophyType::class,
                'choice_label' => function (TrophyType $type): string {
                    return match ($type) {
                        TrophyType::PLATINUM => 'Platine',
                        TrophyType::GOLD => 'Or',
                        TrophyType::SILVER => 'Argent',
                        TrophyType::BRONZE => 'Bronse',
                    };
                },
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])

            ->add("illustrationFile", VichImageType::class, [
                'label' => "Illustration",
                'attr' => [
                    'placeholder' => "Illustration"
                ],
                'required' => false,
                'download_uri' => true,
                'allow_delete' => true,
                'delete_label' => "Supprimer",
                'image_uri' => true,

            ])

            ->add("accomplished", ChoiceType::class, [
                "label" => "Est accomplit ?",
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                "expanded" => true,
                "multiple" => false
            ])

            ->add("priority", IntegerType::class, [
                "label" => "Priorité",
                "required" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trophy::class,
        ]);
    }
}
