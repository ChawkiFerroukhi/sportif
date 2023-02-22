<?php

namespace App\Form;

use App\Entity\Mesure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MesureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdat')
            ->add('updatedat')
            ->add('date')
            ->add('poids')
            ->add('taille')
            ->add('poitrine')
            ->add('cuisse')
            ->add('biceps')
            ->add('age')
            ->add('imc')
            ->add('diagnostic')
            ->add('doctorid')
            ->add('clubid')
            ->add('dossierMedicalid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mesure::class,
        ]);
    }
}
