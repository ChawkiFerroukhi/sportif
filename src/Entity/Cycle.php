<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Cycle.
 *
 * @ORM\Table(name="Cycle", indexes={@ORM\Index(name="coursId", columns={"coursId"}), @ORM\Index(name="clubId", columns={"clubId"})})
 *
 * @ORM\Entity
 */
class Cycle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     *
     * @ORM\Id
     *
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=191, nullable=false)
     */
    private $nom;

    /**
     * @var \Cours
     *
     * @ORM\ManyToOne(targetEntity="Cours")
     *
     * @ORM\JoinColumns({
     *
     *   @ORM\JoinColumn(name="coursId", referencedColumnName="id")
     * })
     */
    private $coursid;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=555, nullable=false)
     */
    private $description;

    /**
     *
     * @ORM\OneToMany(targetEntity=Objectif::class, mappedBy="cycleid")
     */
    private $objectifs;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     *
     * @ORM\JoinColumns({
     *
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id")
     * })
     */
    private $clubid;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", nullable=false)
     */
    private int $duration;

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

    public function getCoursid(): ?Cours
    {
        return $this->coursid;
    }

    public function setCoursid(?Cours $coursid): self
    {
        $this->coursid = $coursid;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Objectif>
     */
    public function getObjectifs(): Collection
    {
        return $this->objectifs;
    }

    public function addObjectif(Objectif $objectif): self
    {
        if (!$this->objectifs->contains($objectif)) {
            $this->objectifs[] = $objectif;
            $objectif->setNiveauid($this);
        }

        return $this;
    }

    public function removeObjectif(Objectif $objectif): self
    {
        if ($this->objectifs->removeElement($objectif)) {
            // set the owning side to null (unless already changed)
            if ($objectif->getNiveauid() === $this) {
                $objectif->setNiveauid(null);
            }
        }

        return $this;
    }

    // add me toString functions
    public function __toString()
    {
        return $this->nom;
    }
}
