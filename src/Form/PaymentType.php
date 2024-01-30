<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class, [ 
                'widget' => 'single_text',
            ])
            ->add('total',null,[
                'required' => true,
                'attr' => [
                    'placeholder' => 'Total payé'
                ]
            ])
            ->add('mode',ChoiceType::class,[
                'choices' => [
                    'Chèque' => 'Chèque',
                    'Virement' => 'Virement',
                    'En Ligne' => 'En Ligne',
                    'Cash' => 'Cash',
                    'Mondat' => 'Mondat',
                ]
            ])
            ->add('status',ChoiceType::class,[
                'choices' => [
                    'En Cours' => 'En Cours',
                    'Payé' => 'Payé',
                    'Annulé' => 'Annulé',
                    'Réfusé' => 'Réfusé',
                ]
            ])
            ->add('ref',null,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Référence'
                ]
            ])
            ->add('designation',null,[
                'required' => true,
                'attr' => [
                    'placeholder' => 'Désignation'
                ]
            ])
            ->add('userid',ChoiceType::class,[
                'choices' => $options['users'],
                'choice_label' => 'Email',
                'placeholder' => 'Choisir un utilisateur',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            'users' => []
        ]);
    }
}
