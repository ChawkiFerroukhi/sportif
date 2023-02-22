<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="Note", indexes={@ORM\Index(name="objectifId", columns={"objectifId"}), @ORM\Index(name="clubId", columns={"clubId"}), @ORM\Index(name="testeId", columns={"testeId"}), @ORM\Index(name="adherantId", columns={"adherantId"})})
 * @ORM\Entity
 */
class Note
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
    private $createdat  ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    private $note;

    /**
     * @var \Objectif
     *
     * @ORM\ManyToOne(targetEntity="Objectif")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="objectifId", referencedColumnName="id")
     * })
     */
    private $objectifid;

    /**
     * @var \Teste
     *
     * @ORM\ManyToOne(targetEntity="Teste")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="testeId", referencedColumnName="id")
     * })
     */
    private $testeid;

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
    private $adherantid;

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

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getObjectifid(): ?Objectif
    {
        return $this->objectifid;
    }

    public function setObjectifid(?Objectif $objectifid): self
    {
        $this->objectifid = $objectifid;

        return $this;
    }

    public function getTesteid(): ?Teste
    {
        return $this->testeid;
    }

    public function setTesteid(?Teste $testeid): self
    {
        $this->testeid = $testeid;

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


}
