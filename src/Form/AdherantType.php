<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Adherant;
use App\Entity\Supervisor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AdherantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdat')
            ->add('updatedat')
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
            ->add('ref')
            ->add('image', PictureType::class,[
                'mapped' => false,
                'required' => false
            ] )
            ->add('nom')
            ->add('prenom')
            ->add('birthdate',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('birthplace')
            ->add('niveauScolaire')
            ->add('ecole')
            ->add('numTel')
            ->add('licence')
            ->add('sexe', ChoiceType::class,[
                'choices' => [
                    'H' => 'H',
                    'F' => 'F'
                ]
            ])
            ->add('maladie',ChoiceType::class,[
                'choices' => $options['maladies'],
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une maladie',
                'required' => false
            ])
            ->add('dossierMedicalId')
            ->add('equipeid',ChoiceType::class,[
                'choices' => $options['equipes'],
                'choice_label' => 'nomsection',
                'placeholder' => 'Choisir un niveau',
                'required' => true
            ])
            ->add('equipe2id',ChoiceType::class,[
                'choices' => $options['equipes'],
                'choice_label' => 'nomsection',
                'placeholder' => 'Choisir un 2éme niveau',
                'required' => false
            ])
            ->add('clubid', null, [
                'label' => 'Club',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un club',
                'required' => false,
            ])
            ->add('supervisorId',ChoiceType::class,[
                'choices' => $options['supervisors'],
                'choice_label' => 'nomprenom',
                'placeholder' => 'Choisir un parent',
                'required' => false
            ])
            
            ->add('supervisor2Id',ChoiceType::class,[
                'choices' => $options['supervisors'],
                'choice_label' => 'nomprenom',
                'placeholder' => 'Choisir un deuxiéme parent',
                'required' => false
            ])
            
            ->add('supervisor_Email', EmailType::class, [
                'required' => false,
                'label' => 'Email',
                'mapped' => false,
            ])
            ->add('supervisor_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('supervisor_ref', TextType::class, [
                'required' => false,
                'label' => 'Reférence',
                'mapped' => false,
            ])
            ->add('supervisor_nom', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])

            ->add('supervisor_prenom', TextType::class, [
                'required' => false,
                'label' => 'Prenom',
                'mapped' => false,
            ])

            ->add('supervisor_numTel', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'mapped' => false,
            ])

            ->add('supervisor_cin', TextType::class, [
                'required' => false,
                'label' => 'CIN',
                'mapped' => false,
            ])

            ->add('supervisor_adresse', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'mapped' => false,
            ])
            

            ////////:

            ->add('supervisor2_Email', EmailType::class, [
                'required' => false,
                'label' => 'Email',
                'mapped' => false,
            ])
            ->add('supervisor2_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('supervisor2_ref', TextType::class, [
                'required' => false,
                'label' => 'Reférence',
                'mapped' => false,
            ])
            ->add('supervisor2_nom', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'mapped' => false,
            ])

            ->add('supervisor2_prenom', TextType::class, [
                'required' => false,
                'label' => 'Prenom',
                'mapped' => false,
            ])

            ->add('supervisor2_numTel', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'mapped' => false,
            ])

            ->add('supervisor2_cin', TextType::class, [
                'required' => false,
                'label' => 'CIN',
                'mapped' => false,
            ])

            ->add('supervisor2_adresse', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'mapped' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherant::class,
            'supervisors' => [],
            'equipes' => [],
            'maladies' => [],
        ]);
    }
}
