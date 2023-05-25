<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            // add roles choicetype
            ->add('roles', ChoiceType::class, [
                'choices' => [


                    'Administrateur' => 'ROLE_ADMIN',
                    'Doctor' => 'ROLE_DOCTOR',
                    'Coach' => 'ROLE_COACH',
                    'Parent' => 'ROLE_SUPERVISOR',
                    'Adherant' => 'ROLE_ADHERANT',

                    'Ajouter Adherant' => 'app_adherant_new',
                    'Modifier Adherant' => 'app_adherant_edit',
                    'Afficher Adherants' => 'app_adherant_index',
                    'Supprimer Adherant' => 'app_adherant_delete',
                    
                    'Ajouter Parent' => 'app_supervisor_new',
                    'Modifier Parent' => 'app_supervisor_edit',
                    'Afficher Parents' => 'app_supervisor_index',
                    'Supprimer Parent' => 'app_supervisor_delete', 

                    'Ajouter Coach' => 'app_coach_new',
                    'Modifier Coach' => 'app_coach_edit',
                    'Afficher Coachs' => 'app_coach_index',
                    'Supprimer Coach' => 'app_coach_delete',

                    'Ajouter Doctor' => 'app_doctor_new',
                    'Modifier Doctor' => 'app_doctor_edit',
                    'Afficher Doctors' => 'app_doctor_index',
                    'Supprimer Doctor' => 'app_doctor_delete',

                    'Ajouter Administrateur' => 'app_administrateur_new',
                    'Modifier Administrateur' => 'app_administrateur_edit',
                    'Afficher Administrateur' => 'app_administrateur_index',
                    'Supprimer Administrateur' => 'app_administrateur_delete',
                ],
                'multiple' => true,
                'expanded' => true,
                
            ])
            ->add('ref')
            ->add('isActive',ChoiceType::class,[
                'choices' => [
                    "Oui" => true,
                    "Non" => false
                ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
