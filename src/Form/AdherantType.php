<?php

namespace App\Form;

use App\Entity\Adherant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdat')
            ->add('updatedat')
            ->add('nom')
            ->add('prenom')
            ->add('birthdate')
            ->add('birthplace')
            ->add('niveauScolaire')
            ->add('ecole')
            ->add('numTel')
            ->add('licence')
            ->add('sexe')
            ->add('maladie')
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
