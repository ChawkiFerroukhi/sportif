<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Seance
 *
 * @ORM\Table(name="Seance", indexes={@ORM\Index(name="clubId", columns={"clubId"}), @ORM\Index(name="equipeId", columns={"equipeId"}), @ORM\Index(name="cycleId", columns={"cycleId"})})
 * @ORM\Entity
 */
class Seance
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
     * @var string
     *
     * @ORM\Column(name="day", type="string", length=5000, nullable=true)
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000, nullable=true)
     */
    private $description;
    
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
     * @var \Cycle
     *
     * @ORM\ManyToOne(targetEntity="Cycle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cycleId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $cycleid;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $clubid;

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

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getCycleid(): ?Cycle
    {
        return $this->cycleid;
    }

    public function setCycleid(?Cycle $cycleid): self
    {
        $this->cycleid = $cycleid;

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

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }


}
