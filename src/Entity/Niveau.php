<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Niveau
 *
 * @ORM\Table(name="Niveau", indexes={@ORM\Index(name="sectionId", columns={"sectionId"}), @ORM\Index(name="clubId", columns={"clubId"})})
 * @ORM\Entity
 */
class Niveau
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
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id")
     * })
     */
    private $clubid;

    /**
     * @var \Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sectionId", referencedColumnName="id")
     * })
     */
    private $sectionid;

     /**
     *
     * @ORM\OneToMany(targetEntity=Equipe::class, mappedBy="niveauid")
     */
    private $equipes;

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

    public function getSectionid(): ?Section
    {
        return $this->sectionid;
    }

    public function setSectionid(?Section $sectionid): self
    {
        $this->sectionid = $sectionid;

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipes->setNiveauid($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getNiveauid() === $this) {
                $equipe->setNiveauid(null);
            }
        }

        return $this;
    }
    public function getFCreatedat(): ?string
    {
        $newDate = $this->createdat->format('m/d/Y');

        return $newDate;       
    }

    public function __toString()
    {
        return $this->nom;
    }


}
