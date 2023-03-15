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
            ->add('categrieid')
            ->add('equipeid')
            ->add('demeCategorieid')
            ->add('clubid')
            ->add('supervisorId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherant::class,
        ]);
    }
}
