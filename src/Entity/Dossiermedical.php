<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Dossiermedical
 *
 * @ORM\Table(name="DossierMedical", uniqueConstraints={@ORM\UniqueConstraint(name="DossierMedical_adherantid_unique", columns={"adherantid"})}, indexes={@ORM\Index(name="clubId", columns={"clubId"})})
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
     * @ORM\Column(name="createdAt", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
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
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $clubid;

    /**
     * @var \Adherant
     *
     * @ORM\ManyToOne(targetEntity="Adherant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adherantid", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $adherantid;

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
        return $this->adherantid;
    }

    public function setAdherantid(?Adherant $adherantid): self
    {
        $this->adherantid = $adherantid;

        return $this;
    }

    /**
     * @return Collection<int, Mesure>
     */
    public function getMesures(): Collection
    {
        return $this->mesures;
    }

    public function getMesure(): Mesure
    {
        $msr = $this->mesures->toArray();
        usort($msr, function ($a, $b) {
            return strtotime($b->getDate()->format('Y-m-d')) - strtotime($a->getDate()->format('Y-m-d'));
        });
        
        return isset($msr[0]) ? $msr[0] : new Mesure();
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
