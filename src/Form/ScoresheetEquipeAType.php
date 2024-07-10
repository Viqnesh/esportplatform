<?php

namespace App\Form;

use App\Entity\Scoresheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ScoresheetEquipeAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('scoreReport')
            ->add('state' , ChoiceType::class, [
                'choices'  => [
                    'Victoire' => "W",
                    'Défaite' => "L",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scoresheet::class,
        ]);
    }
}
