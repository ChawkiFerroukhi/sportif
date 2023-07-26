<?php

namespace App\Form;

use App\Entity\Mesure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
            ->add('diagnostic',CKEditorType::class,[
                'attr' => [
                    'placeholder' => 'Description',
                ],
                'required' => true
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
