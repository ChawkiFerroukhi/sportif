<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Equipe
 *
 * @ORM\Table(name="Equipe", indexes={@ORM\Index(name="niveauId", columns={"niveauId"}), @ORM\Index(name="clubId", columns={"clubId"}), @ORM\Index(name="doctorId", columns={"doctorId"}), @ORM\Index(name="coachId", columns={"coachId"})})
 * @ORM\Entity
 */
class Equipe
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
     * @var \Niveau
     *
     * @ORM\ManyToOne(targetEntity="Niveau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="niveauId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $niveauid;

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
     * @var \Coach
     *
     * @ORM\ManyToOne(targetEntity="Coach")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="coachId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $coachid;

    /**
     * @ORM\ManyToMany(targetEntity="Staff")
     * @ORM\JoinTable(
     *     name="equipe_staff",
     *     joinColumns={@ORM\JoinColumn(name="equipeid", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="staffid", referencedColumnName="id")}
     * )
     */
    private $staffMembers;

    /**
     *
     * @ORM\OneToMany(targetEntity=Adherant::class, mappedBy="equipeid")
     */
    private $adherants;
    
    /**
     *
     * @ORM\OneToMany(targetEntity=Adherant::class, mappedBy="equipe2id")
     */
    private $adherants2;

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

    public function getNiveauid(): ?Niveau
    {
        return $this->niveauid;
        true;
    }

    public function setNiveauid(?Niveau $niveauid): self
    {
        $this->niveauid = $niveauid;

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

    public function getCoachid(): ?Coach
    {
        return $this->coachid;
    }

    public function setCoachid(?Coach $coachid): self
    {
        $this->coachid = $coachid;

        return $this;
    }

    public function __ToString(): string {
        return $this->nom;
    }

    /**
     * @return Collection<int, Adherant>
     */
    public function getAdherants(): Collection
    {
        $adhs = [];
        foreach($this->adherants2 as $adherant) {
            if (!$this->adherants->contains($adherant)) {
                $adhs[] = $adherant;
            }
        }
        $resArray = array_merge($this->adherants->toArray(), $adhs);
        return new ArrayCollection($resArray);
    }

    public function addAdherant(Adherant $adherant): self
    {
        if (!$this->adherants->contains($adherant)) {
            $this->adherants[] = $adherant;
            $adherants->setEquipeid($this);
        }

        return $this;
    }

    public function removeAdherant(Adherant $adherant): self
    {
        if ($this->adherants->removeElement($adherant)) {
            // set the owning side to null (unless already changed)
            if ($adherant->getEquipeid() === $this) {
                $adherant->setEquipeid(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, Adherant>
     */
    public function getAdherants2(): Collection
    {
        $adhs = [];
        foreach($this->adherants2 as $adherant) {
            if (!$this->adherants->contains($adherant)) {
                $adhs[] = $adherant;
            }
        }
        return new ArrayCollection($adhs);
    }

    public function addAdherant2(Adherant $adherant): self
    {
        if (!$this->adherants2->contains($adherant)) {
            $this->adherants2[] = $adherant;
            $adherants2->setEquipe2id($this);
        }

        return $this;
    }

    public function removeAdherant2(Adherant $adherant): self
    {
        if ($this->adherants2->removeElement($adherant)) {
            // set the owning side to null (unless already changed)
            if ($adherant->getEquipe2id() === $this) {
                $adherant->setEquipe2id(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection|Staff[]
     */
    public function getStaffMembers(): Collection
    {
        return $this->staffMembers;
    }

    public function addStaffMember(Staff $staff): self
    {
        if (!$this->staffMembers->contains($staff)) {
            $this->staffMembers[] = $staff;
        }

        return $this;
    }

    public function removeStaffMember(Staff $staff): self
    {
        $this->staffMembers->removeElement($staff);

        return $this;
    }

    public function nomsection(): string {
        return $this->niveauid->getSectionid()->getNom()." : ".$this->getNom();
    }

}
