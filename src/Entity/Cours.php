<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Cours.
 *
 * @ORM\Table(name="Cours", indexes={@ORM\Index(name="niveauId", columns={"niveauId"}), @ORM\Index(name="clubId", columns={"clubId"})})
 *
 * @ORM\Entity
 */
class Cours
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=555, nullable=false)
     */
    private $description;

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
     * @var \Niveau
     *
     * @ORM\ManyToOne(targetEntity="Niveau")
     *
     * @ORM\JoinColumns({
     *
     *   @ORM\JoinColumn(name="niveauId", referencedColumnName="id")
     * })
     */
    private $niveauid;

    /**
     *
     * @ORM\OneToMany(targetEntity=Cycle::class, mappedBy="coursid")
     */
    private $cycles;

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

    public function getClubid(): ?Club
    {
        return $this->clubid;
    }

    public function setClubid(?Club $clubid): self
    {
        $this->clubid = $clubid;

        return $this;
    }

    public function getNiveauid(): ?Niveau
    {
        return $this->niveauid;
    }

    public function setNiveauid(?Niveau $niveauid): self
    {
        $this->niveauid = $niveauid;

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
    /**
     * @return Collection<int, Cycle>
     */
    public function getCycles(): Collection
    {
        return $this->cycles;
    }

    public function addCycle(Cycle $cycle): self
    {
        if (!$this->cycles->contains($cycle)) {
            $this->cycles[] = $cycle;
            $cycle->setNiveauid($this);
        }

        return $this;
    }

    public function removeCycle(Cycle $cycle): self
    {
        if ($this->cycles->removeElement($cycle)) {
            // set the owning side to null (unless already changed)
            if ($cycle->getNiveauid() === $this) {
                $cycle->setNiveauid(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom;
    }
}
