<?php

namespace App\Form;

use App\Entity\Administrateur;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdministrateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('Email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'required' => false,
                'mapped' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                
            ])
            ->add('image', PictureType::class,[
                'mapped' => false,
                'required' => false
            ] )
            ->add('ref')
            ->add('numTel')
            ->add('cin')
            ->add('adresse')
            ->add('poste',ChoiceType::class,[
                'choices' => $options['postes'],
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une poste',
                'mapped' => false,
                'required' => false
            ])
            ->add('clubid', null, [
                'label' => 'Club',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un club',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Administrateur::class,
            'postes' => []
        ]);
    }
}
