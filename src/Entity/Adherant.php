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
        $roles[] = 'ROLE_ADHERANT';
        $roles = array_unique($roles);
        $rls = [];
        foreach($roles as $role) {
            $rls[$role] = $role;
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
