<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Adherant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdherantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdat')
            ->add('updatedat')
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
                    'Aucune' => null,
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
            ->add('equipeid', null, [
                'label' => 'Equipe',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une équipe',
                'required' => false,
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
                'required' => true,
            ])
            ->add('supervisorId', null, [
                'label' => 'Superviseur',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un superviseur',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherant::class,
        ]);
    }
}
