<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Supervisor
 *
 * @ORM\Table(name="Supervisor", indexes={})
 * @ORM\Entity
 */
class Supervisor extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=191, nullable=false)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="CIN", type="string", length=191, nullable=false)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=191, nullable=false)
     */
    private $adresse;

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
     *
     * @ORM\OneToMany(targetEntity=Adherant::class, mappedBy="supervisorId")
     */
    private $adherants;
    /**
     *
     * @ORM\OneToMany(targetEntity=Adherant::class, mappedBy="supervisor2Id")
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function __ToString(): string {
        return $this->nom." ".$this->prenom;
    }

    /**
     * @return Collection<int, Adherant>
     */
    public function getAdherants(): Collection
    {
        return $this->adherants;
    }

    public function addAdherant(Adherant $adherant): self
    {
        if (!$this->adherants->contains($adherant)) {
            $this->adherants[] = $adherant;
            $adherants->setNiveauid($this);
        }

        return $this;
    }

    public function removeAdherant(Adherant $adherant): self
    {
        if ($this->adherants->removeElement($adherant)) {
            // set the owning side to null (unless already changed)
            if ($adherant->getNiveauid() === $this) {
                $adherant->setNiveauid(null);
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
            $adherants2->setNiveauid($this);
        }

        return $this;
    }

    public function removeAdherant2(Adherant $adherant): self
    {
        if ($this->adherants2->removeElement($adherant)) {
            // set the owning side to null (unless already changed)
            if ($adherant->getNiveauid() === $this) {
                $adherant->setNiveauid(null);
            }
        }

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
        $roles[] = 'ROLE_SUPERVISOR';
        $roles = array_unique($roles);
        $rls = [];
        foreach($roles as $role) {
            $rls[$role] = true;
        }

         return $rls;
    }
}
