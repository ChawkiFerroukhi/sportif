<?php

namespace App\Form;

use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipeid', ChoiceType::class, [
                'choices' => $options['choices'],
                'choice_label' => 'nom', // optional: specify the property to use as the label
            ])
            ->add('date',TimeType::class, [ 
                'widget' => 'single_text',
            ])
            ->add('day',ChoiceType::class, [
                'choices' => [
                    'Lundi' => 'Lundi',
                    'Mardi' => 'Mardi',
                    'Mercredi' => 'Mercredi',
                    'Jeudi' => 'Jeudi',
                    'Vendredi' => 'Vendredi',
                    'Samedi' => 'Samedi',
                    'Dimanche' => 'Dimanche',
                ],
            ])
            ->add('description', TinymceType::class, [
                "attr" => [
                    "toolbar" => "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
            'choices' => []
        ]);
    }

}
