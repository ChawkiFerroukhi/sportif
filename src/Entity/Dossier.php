<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Supervisor;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Dossier
 *
 * @ORM\Table(name="Dossier", indexes={@ORM\Index(name="adherantId", columns={"adherantId"}) })
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Dossier implements \Serializable
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="string", length=255)
     */
    private $document;
    

    /**
     * @Vich\UploadableField(mapping="user_file", fileNameProperty="document")
     * @var File|null
    */
    private  $documentFile = null;

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
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    protected $clubid;

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

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    public function setDocumentFile(?File $documentFile): void
    {
        $this->documentFile = $documentFile;

        if ($documentFile instanceof UploadedFile) {
            $this->updatedat = new \DateTime();
        }
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

    public function getClubid(): ?Club
    {
        return $this->clubid;
    }

    public function setClubid(?Club $clubid): self
    {
        $this->clubid = $clubid;

        return $this;
    }

    public function serialize()
    {
        $this->documentFile = base64_encode($this->documentFile);
    }

    public function unserialize($serialized)
    {
        $this->documentFile = base64_decode($this->documentFile);

    }
}