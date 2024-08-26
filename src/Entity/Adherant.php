<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Supervisor;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Adherant
 *
 * @ORM\Table(name="Adherant", indexes={@ORM\Index(name="equipeId", columns={"equipeId"}), @ORM\Index(name="supervisorId", columns={"supervisorId"}),  @ORM\Index(name="supervisor2Id", columns={"supervisor2Id"})})
 * @Vich\Uploadable
 * @ORM\Entity
 */
class Adherant extends User
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdat  ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=191, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=191, nullable=false)
     */
    private $prenom;

    /**
     * @var \Date
     *
     * @ORM\Column(name="birthDate", type="date", nullable=false)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="birthPlace", type="string", length=191, nullable=false)
     */
    private $birthplace;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau_scolaire", type="string", length=191, nullable=false)
     */
    private $niveauScolaire;

    /**
     * @var string
     *
     * @ORM\Column(name="ecole", type="string", length=191, nullable=false)
     */
    private $ecole;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=191, nullable=true)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="licence", type="string", length=191, nullable=false)
     */
    private $licence;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=191, nullable=false)
     */
    private $sexe;

    /**
     * @var \Maladie
     * 
     * @ORM\ManyToOne(targetEntity="Maladie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="maladie", referencedColumnName="id" )
     * })
     */
    private $maladie;

    /**
     * @ORM\Column(type="string")
     */
    protected ?string $ref;

    /**
     * @var \Dossiermedical
     *
     * @ORM\ManyToOne(targetEntity="Dossiermedical")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dossier_medicalId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $dossierMedicalId;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipeId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $equipeid;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipe2Id", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $equipe2id;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    protected $clubid;

    /**
     * @var \Supervisor
     *
     * @ORM\ManyToOne(targetEntity="Supervisor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supervisorId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $supervisorId;

    /**
     * @var \Supervisor
     *
     * @ORM\ManyToOne(targetEntity="Supervisor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supervisor2Id", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $supervisor2Id;

    /**
     *
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="adherantid")
     */
    private $notes;

    /**
     *
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="adherantid")
     */
    private $pictures;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function getFBirthdate(): ?string
    {
        $newDate = $this->birthdate->format('m/d/Y');

        return $newDate;       
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getBirthplace(): ?string
    {
        return $this->birthplace;
    }

    public function setBirthplace(string $birthplace): self
    {
        $this->birthplace = $birthplace;

        return $this;
    }

    public function getNiveauScolaire(): ?string
    {
        return $this->niveauScolaire;
    }

    public function setNiveauScolaire(string $niveauScolaire): self
    {
        $this->niveauScolaire = $niveauScolaire;

        return $this;
    }

    public function getEcole(): ?string
    {
        return $this->ecole;
    }

    public function setEcole(string $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getLicence(): ?string
    {
        return $this->licence;
    }

    public function setLicence(string $licence): self
    {
        $this->licence = $licence;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getMaladie(): ?Maladie
    {
        return $this->maladie;
    }

    public function setMaladie(?Maladie $maladie): self
    {
        $this->maladie = $maladie;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDossierMedicalId(): ?Dossiermedical
    {
        return $this->dossierMedicalId;
    }

    public function setDossierMedicaId(?Dossiermedical $dossierMedicalid): self
    {
        $this->dossierMedicalId = $dossierMedicalid;

        return $this;
    }

    public function getEquipeid(): ?Equipe
    {
        return $this->equipeid;
    }

    public function setEquipeid(?Equipe $equipeid): self
    {
        $this->equipeid = $equipeid;

        return $this;
    }

    public function getEquipe2id(): ?Equipe
    {
        return $this->equipe2id;
    }

    public function setEquipe2id(?Equipe $equipeid): self
    {
        $this->equipe2id = $equipeid;

        return $this;
    }

    public function getClubid(): ?Club
    {
        return $this->clubid;
    }

    public function setClubid(?Club $clubid): self
    {
        $this->clubid = $clubid;

        return $this;
    }

    public function getSupervisorId(): ?Supervisor
    {
        return $this->supervisorId;
    }

    public function setSupervisorId(?Supervisor $supervisorId): self
    {
        $this->supervisorId = $supervisorId;

        return $this;
    }

    public function getSupervisor2Id(): ?Supervisor
    {
        return $this->supervisor2Id;
    }

    public function setSupervisor2Id(?Supervisor $supervisor2Id): self
    {
        $this->supervisor2Id = $supervisor2Id;

        return $this;
    }
    
    public function getNomprenom()
    {
        return $this->nom.' '.$this->prenom;
    }
    /**
    * @see UserInterface
    */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $rolesArray = [

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

            'Ajouter Staff' => 'app_staff_new',
            'Modifier Staff' => 'app_staff_edit',
            'Afficher Staffs' => 'app_staff_index',
            'Fiche Staff' => 'app_staff_show',
            'Supprimer Staff' => 'app_staff_delete',
            
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
        ];
        $rls = [];
        $rls['ROLE_ADHERANT'] = true;
        $roles = array_unique($roles);
        
        foreach($rolesArray as $role) {
            if(in_array($role,$roles)) {
                $rls[$role] = true;
            } else {
                $rls[$role] = false;
            }
        }

         return $rls;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setAdherantid($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getAdherantid() === $this) {
                $note->setAdherantid(null);
            }
        }

        return $this;
    }


}
