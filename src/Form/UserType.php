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

                    'Ajouter Acteur' => 'app_acteur_new',
                    'Modifier Acteur' => 'app_acteur_edit',
                    'Afficher Acteurs' => 'app_acteur_index',
                    'Fiche Acteur' => 'app_acteur_show',
                    'Supprimer Acteur' => 'app_acteur_delete',

                    'Ajouter Adherant' => 'app_adherant_new',
                    'Modifier Adherant' => 'app_adherant_edit',
                    'Afficher Adherants' => 'app_adherant_index',
                    'Fiche Adherant' => 'app_adherant_show',
                    'Supprimer Adherant' => 'app_adherant_delete',

                    'Ajouter Administrateur' => 'app_administrateur_new',
                    'Modifier Administrateur' => 'app_administrateur_edit',
                    'Afficher Administrateurs' => 'app_administrateur_index',
                    'Fiche Administrateur' => 'app_administrateur_show',
                    'Supprimer Administrateur' => 'app_administrateur_delete',

                    'Ajouter Blog' => 'app_blog_new',
                    'Modifier Blog' => 'app_blog_edit',
                    'Afficher Blogs' => 'app_blog_index',
                    'Fiche Blog' => 'app_blog_show',
                    'Supprimer Blog' => 'app_blog_delete',

                    'Ajouter Club' => 'app_club_new',
                    'Modifier Club' => 'app_club_edit',
                    'Afficher Clubs' => 'app_club_index',
                    'Fiche Club' => 'app_club_show',
                    'Statistiques Club' => 'app_club_stats',
                    'Supprimer Club' => 'app_club_delete', 

                    'Ajouter Coach' => 'app_coach_new',
                    'Modifier Coach' => 'app_coach_edit',
                    'Afficher Coachs' => 'app_coach_index',
                    'Fiche Coach' => 'app_coach_show',
                    'Supprimer Coach' => 'app_coach_delete',

                    'Ajouter Cours' => 'app_cours_new',
                    'Modifier Cours' => 'app_cours_edit',
                    'Afficher Courss' => 'app_cours_index',
                    'Fiche Cours' => 'app_cours_show',
                    'Supprimer Cours' => 'app_cours_delete',

                    'Ajouter Cycle' => 'app_cycle_new',
                    'Modifier Cycle' => 'app_cycle_edit',
                    'Afficher Cycles' => 'app_cycle_index',
                    'Fiche Cycle' => 'app_cycle_show',
                    'Supprimer Cycle' => 'app_cycle_delete',

                    'Ajouter Decaissement' => 'app_decaissement_new',
                    'Modifier Decaissement' => 'app_decaissement_edit',
                    'Afficher Decaissements' => 'app_decaissement_index',
                    'Fiche Decaissement' => 'app_decaissement_show',
                    'Supprimer Decaissement' => 'app_decaissement_delete',

                    'Ajouter Docteur' => 'app_doctor_new',
                    'Modifier Docteur' => 'app_doctor_edit',
                    'Afficher Doctors' => 'app_doctor_index',
                    'Fiche Docteur' => 'app_doctor_show',
                    'Supprimer Docteur' => 'app_doctor_delete',

                    'Ajouter Dossier Medical' => 'app_dossiermedical_new',
                    'Modifier Dossier Medical' => 'app_dossiermedical_edit',
                    'Afficher Dossiers Medicaux' => 'app_dossiermedical_index',
                    'Fiche Dossier Medical' => 'app_dossiermedical_show',
                    'Supprimer Dossier Medical' => 'app_dossiermedical_delete',

                    'Ajouter Dossier' => 'app_dossier_new',
                    'Modifier Dossier' => 'app_dossier_edit',
                    'Afficher Dossiers' => 'app_dossier_index',
                    'Fiche Dossier' => 'app_dossier_show',
                    'Supprimer Dossier' => 'app_dossier_delete',

                    'Ajouter Encaissement' => 'app_encaissement_new',
                    'Modifier Encaissement' => 'app_encaissement_edit',
                    'Afficher Encaissements' => 'app_encaissement_index',
                    'Fiche Encaissement' => 'app_encaissement_show',
                    'Supprimer Encaissement' => 'app_encaissement_delete',

                    'Ajouter Equipe' => 'app_equipe_new',
                    'Modifier Equipe' => 'app_equipe_edit',
                    'Afficher Equipes' => 'app_equipe_index',
                    'Fiche Equipe' => 'app_equipe_show',
                    'Supprimer Equipe' => 'app_equipe_delete',

                    'Ajouter Income' => 'app_income_new',
                    'Modifier Income' => 'app_income_edit',
                    'Afficher Incomes' => 'app_income_index',
                    'Fiche Income' => 'app_income_show',
                    'Supprimer Income' => 'app_income_delete',

                    'Ajouter Maladie' => 'app_maladie_new',
                    'Modifier Maladie' => 'app_maladie_edit',
                    'Afficher Maladies' => 'app_maladie_index',
                    'Fiche Maladie' => 'app_maladie_show',
                    'Supprimer Maladie' => 'app_maladie_delete',

                    'Ajouter Mesure' => 'app_mesure_new',
                    'Modifier Mesure' => 'app_mesure_edit',
                    'Afficher Mesures' => 'app_mesure_index',
                    'Fiche Mesure' => 'app_mesure_show',
                    'Supprimer Mesure' => 'app_mesure_delete',

                    'Envoyer Newsletter' => 'app_newsletter_index',

                    'Ajouter Niveau' => 'app_niveau_new',
                    'Modifier Niveau' => 'app_niveau_edit',
                    'Afficher Niveaus' => 'app_niveau_index',
                    'Fiche Niveau' => 'app_niveau_show',
                    'Supprimer Niveau' => 'app_niveau_delete',

                    'Ajouter Note' => 'app_note_new',
                    'Modifier Note' => 'app_note_edit',
                    'Afficher Notes' => 'app_note_index',
                    'Fiche Note' => 'app_note_show',
                    'Supprimer Note' => 'app_note_delete',

                    'Ajouter Objectif' => 'app_objectif_new',
                    'Modifier Objectif' => 'app_objectif_edit',
                    'Afficher Objectifs' => 'app_objectif_index',
                    'Fiche Objectif' => 'app_objectif_show',
                    'Supprimer Objectif' => 'app_objectif_delete',

                    'Ajouter Payment' => 'app_payment_new',
                    'Modifier Payment' => 'app_payment_edit',
                    'Afficher Payments' => 'app_payment_index',
                    'Fiche Payment' => 'app_payment_show',
                    'Supprimer Payment' => 'app_payment_delete',

                    'Ajouter Poste' => 'app_poste_new',
                    'Modifier Poste' => 'app_poste_edit',
                    'Afficher Postes' => 'app_poste_index',
                    'Fiche Poste' => 'app_poste_show',
                    'Supprimer Poste' => 'app_poste_delete',

                    'Ajouter Presence' => 'app_presence_new',
                    'Modifier Presence' => 'app_presence_edit',
                    'Afficher Presences' => 'app_presence_index',
                    'Fiche Presence' => 'app_presence_show',
                    'Supprimer Presence' => 'app_presence_delete',

                    'Ajouter Seance' => 'app_seance_new',
                    'Modifier Seance' => 'app_seance_edit',
                    'Afficher Seances' => 'app_seance_index',
                    'Fiche Seance' => 'app_seance_show',
                    'Supprimer Seance' => 'app_seance_delete',

                    'Ajouter Section' => 'app_section_new',
                    'Modifier Section' => 'app_section_edit',
                    'Afficher Sections' => 'app_section_index',
                    'Fiche Section' => 'app_section_show',
                    'Supprimer Section' => 'app_section_delete',
                    
                    'Ajouter Parent' => 'app_supervisor_new',
                    'Modifier Parent' => 'app_supervisor_edit',
                    'Afficher Parents' => 'app_supervisor_index',
                    'Fiche Parent' => 'app_supervisor_show',
                    'Supprimer Parent' => 'app_supervisor_delete',

                    'Ajouter Teste' => 'app_teste_new',
                    'Modifier Teste' => 'app_teste_edit',
                    'Afficher Testes' => 'app_teste_index',
                    'Fiche Teste' => 'app_teste_show',
                    'Supprimer Teste' => 'app_teste_delete',

                    'Ajouter User' => 'app_user_new',
                    'Modifier User' => 'app_user_edit',
                    'Afficher Users' => 'app_user_index',
                    'Fiche User' => 'app_user_show',
                    'Supprimer User' => 'app_user_delete',
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
