<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('niveauid', null, [
                'label' => 'Niveau',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un niveau',
                'required' => true,
            ])
            ->add('doctorid', null, [
                'label' => 'Docteur',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un docteur',
                'required' => true,
            ])
            ->add('clubid', null, [
                'label' => 'Club',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un club',
                'required' => true,
            ])
            ->add('coachid', null, [
                'label' => 'Coach',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un coach',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
