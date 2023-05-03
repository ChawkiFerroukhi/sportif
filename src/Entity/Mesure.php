<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Mesure
 *
 * @ORM\Table(name="Mesure", indexes={@ORM\Index(name="dossier_medicalId", columns={"dossier_medicalId"}), @ORM\Index(name="doctorId", columns={"doctorId"}), @ORM\Index(name="clubId", columns={"clubId"})})
 * @ORM\Entity
 */
class Mesure
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="poids", type="float", precision=10, scale=0, nullable=false)
     */
    private $poids;

    /**
     * @var float
     *
     * @ORM\Column(name="taille", type="float", precision=10, scale=0, nullable=false)
     */
    private $taille;

    /**
     * @var float
     *
     * @ORM\Column(name="poitrine", type="float", precision=10, scale=0, nullable=false)
     */
    private $poitrine;

    /**
     * @var float
     *
     * @ORM\Column(name="cuisse", type="float", precision=10, scale=0, nullable=false)
     */
    private $cuisse;

    /**
     * @var float
     *
     * @ORM\Column(name="biceps", type="float", precision=10, scale=0, nullable=false)
     */
    private $biceps;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var float
     *
     * @ORM\Column(name="imc", type="float", precision=10, scale=0, nullable=false)
     */
    private $imc;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostic", type="string", length=191, nullable=false)
     */
    private $diagnostic;

    /**
     * @var \Doctor
     *
     * @ORM\ManyToOne(targetEntity="Doctor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="doctorId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $doctorid;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $clubid;

    /**
     * @var \Dossiermedical
     *
     * @ORM\ManyToOne(targetEntity="Dossiermedical")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dossier_medicalId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $dossierMedicalid;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function getFDate(): ?string
    {
        $newDate = $this->date->format('m/d/Y');

        return $newDate;    
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPoitrine(): ?float
    {
        return $this->poitrine;
    }

    public function setPoitrine(float $poitrine): self
    {
        $this->poitrine = $poitrine;

        return $this;
    }

    public function getCuisse(): ?float
    {
        return $this->cuisse;
    }

    public function setCuisse(float $cuisse): self
    {
        $this->cuisse = $cuisse;

        return $this;
    }

    public function getBiceps(): ?float
    {
        return $this->biceps;
    }

    public function setBiceps(float $biceps): self
    {
        $this->biceps = $biceps;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getImc(): ?float
    {
        return $this->imc;
    }

    public function setImc(float $imc): self
    {
        $this->imc = $imc;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getDoctorid(): ?Doctor
    {
        return $this->doctorid;
    }

    public function setDoctorid(?Doctor $doctorid): self
    {
        $this->doctorid = $doctorid;

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

    public function getDossierMedicalid(): ?Dossiermedical
    {
        return $this->dossierMedicalid;
    }

    public function setDossierMedicalid(?Dossiermedical $dossierMedicalid): self
    {
        $this->dossierMedicalid = $dossierMedicalid;

        return $this;
    }


}
