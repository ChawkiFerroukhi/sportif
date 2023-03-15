<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Dossiermedical
 *
 * @ORM\Table(name="DossierMedical", uniqueConstraints={@ORM\UniqueConstraint(name="DossierMedical_adherantId_unique", columns={"adherantId"})}, indexes={@ORM\Index(name="clubId", columns={"clubId"})})
 * @ORM\Entity
 */
class Dossiermedical
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
     * @ORM\Column(name="createdAt", type="datetime", nullable=false, options={"default"="current_timestamp(3)"})
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
     */
    private $updatedat;

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
     * @var \Adherant
     *
     * @ORM\ManyToOne(targetEntity="Adherant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adherantId", referencedColumnName="id")
     * })
     */
    private $adherantId;

    /**
     *
     * @ORM\OneToMany(targetEntity=Mesure::class, mappedBy="dossierMedicalid")
     */
    private $mesures;

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

    public function getClubid(): ?Club
    {
        return $this->clubid;
    }

    public function setClubid(?Club $clubid): self
    {
        $this->clubid = $clubid;

        return $this;
    }

    public function getAdherantid(): ?Adherant
    {
        return $this->adherantId;
    }

    public function setAdherantid(?Adherant $adherantId): self
    {
        $this->adherantId = $adherantId;

        return $this;
    }

    /**
     * @return Collection<int, Mesure>
     */
    public function getMesures(): Collection
    {
        return $this->mesures;
    }

    public function addMesure(Mesure $mesure): self
    {
        if (!$this->mesures->contains($mesure)) {
            $this->mesures[] = $mesure;
            $mesures->setDossierMedicalid($this);
        }

        return $this;
    }

    public function removeMesure(Mesure $mesure): self
    {
        if ($this->mesures->removeElement($mesure)) {
            // set the owning side to null (unless already changed)
            if ($mesure->getDossierMedicalid() === $this) {
                $mesure->setDossierMedicalid(null);
            }
        }

        return $this;
    }


    public function __ToString(): string {
        return $this->id;
    }

}
