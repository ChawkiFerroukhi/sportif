<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('doctorid',ChoiceType::class,[
                'choices' => $options['doctors'],
                'choice_label' => 'nomprenom',
                'placeholder' => 'Choisir un Docteur',
                'required' => false
            ])
            ->add('coachid',ChoiceType::class,[
                'choices' => $options['coachs'],
                'choice_label' => 'nomprenom',
                'placeholder' => 'Choisir un Entraîneur',
                'required' => false
            ])
            ->add('coach_Email', EmailType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])
            ->add('coach_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('coach_ref', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])
            ->add('coach_nom', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])

            ->add('coach_prenom', TextType::class, [
                'required' => false,
                'label' => 'Prenom',
                'mapped' => false,
            ])

            ->add('coach_numTel', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'mapped' => false,
            ])

            ->add('coach_cin', TextType::class, [
                'required' => false,
                'label' => 'CIN',
                'mapped' => false,
            ])
            ->add('coach_adresse', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'mapped' => false,
            ])

            ->add('doctor_nom', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])
            ->add('doctor_Email', EmailType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])
            ->add('doctor_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('doctor_ref', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])
            ->add('doctor_prenom', TextType::class, [
                'required' => false,
                'label' => 'Prenom',
                'mapped' => false,
            ])

            ->add('doctor_numTel', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'mapped' => false,
            ])

            ->add('doctor_cin', TextType::class, [
                'required' => false,
                'label' => 'CIN',
                'mapped' => false,
            ])

            ->add('doctor_adresse', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
            'coachs' => [],
            'doctors' => [],
        ]);
    }
}
