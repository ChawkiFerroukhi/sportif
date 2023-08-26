<?php

namespace App\Form;

use App\Entity\Acteur;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('imageFile', VichImageType::class,[
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ] )
            ->add('poste',ChoiceType::class,[
                'choices' => $options['postes'],
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une poste',
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
            'data_class' => Acteur::class,
            'postes' => []
        ]);
    }
}
