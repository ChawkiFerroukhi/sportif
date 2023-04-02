<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Supervisor;

/**
 * Adherant
 *
 * @ORM\Table(name="Adherant", indexes={@ORM\Index(name="equipeId", columns={"equipeId"}), @ORM\Index(name="categrieId", columns={"categrieId"}), @ORM\Index(name="clubId", columns={"clubId"}), @ORM\Index(name="Deme_categorieId", columns={"Deme_categorieId"}), @ORM\Index(name="supervisorId", columns={"supervisorId"})})
 * @ORM\Entity
 */
class Adherant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @ORM\Column(name="num_tel", type="string", length=191, nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="maladie", type="string", length=191, nullable=false)
     */
    private $maladie;

    /**
     * @var \Dossiermedical
     *
     * @ORM\ManyToOne(targetEntity="Dossiermedical")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dossier_medicalId", referencedColumnName="id")
     * })
     */
    private $dossierMedicalId;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categrieId", referencedColumnName="id")
     * })
     */
    private $categrieid;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipeId", referencedColumnName="id")
     * })
     */
    private $equipeid;

    /**
     * @var \Demecategorie
     *
     * @ORM\ManyToOne(targetEntity="Demecategorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Deme_categorieId", referencedColumnName="id")
     * })
     */
    private $demeCategorieid;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id")
     * })
     */
    private $clubid;

    /**
     * @var \Supervisor
     *
     * @ORM\ManyToOne(targetEntity="Supervisor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supervisorId", referencedColumnName="id")
     * })
     */
    private $supervisorId;

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

    public function getMaladie(): ?string
    {
        return $this->maladie;
    }

    public function setMaladie(string $maladie): self
    {
        $this->maladie = $maladie;

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

    public function getCategrieid(): ?Categorie
    {
        return $this->categrieid;
    }

    public function setCategrieid(?Categorie $categrieid): self
    {
        $this->categrieid = $categrieid;

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

    public function getDemeCategorieid(): ?Demecategorie
    {
        return $this->demeCategorieid;
    }

    public function setDemeCategorieid(?Demecategorie $demeCategorieid): self
    {
        $this->demeCategorieid = $demeCategorieid;

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


}
