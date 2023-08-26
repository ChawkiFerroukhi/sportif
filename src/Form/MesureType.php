<?php

namespace App\Form;

use App\Entity\Mesure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MesureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class, [ 
                'widget' => 'single_text',
            ])
            ->add('poids')
            ->add('taille')
            ->add('poitrine')
            ->add('cuisse')
            ->add('biceps')
            ->add('age')
            ->add('diagnostic', TinymceType::class, [
                "attr" => [
                    "toolbar" => "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
                ]
            ])
            ->add('doctorid',ChoiceType::class,[
                'choices' => $options['doctors'],
                'choice_label' => 'nomprenom',
                'placeholder' => 'Choisir un Docteur',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mesure::class,
            'doctors' => []
        ]);
    }
}
