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
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('ref')
            ->add('pictureFile', VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
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
                    'M' => 'M',
                    'F' => 'F'
                ]
            ])
            ->add('maladie',ChoiceType::class,[
                'choices' => [
                    'Aucune' => 'Aucune',
                    'Maladie 1' => 'Maladie 1',
                    'Maladie 2' => 'Maladie 2',
                    'Maladie 3' => 'Maladie 3',
                    
                ]
            ])
            ->add('dossierMedicalId')
            ->add('categrieid', null, [
                'label' => 'Catégorie',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une catégorie',
                'required' => true,
            ])
            ->add('equipeid',ChoiceType::class,[
                'choices' => $options['equipes'],
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une equipe',
                'required' => false
            ])
            ->add('demeCategorieid', null, [
                'label' => 'Catégorie',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
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
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherant::class,
            'supervisors' => [],
            'equipes' => [],
        ]);
    }
}
