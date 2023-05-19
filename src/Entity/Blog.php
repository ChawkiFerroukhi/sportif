<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Blog
 *
 * @ORM\Table(name="Blog")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Blog implements \Serializable
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
     * @ORM\Column(name="title", type="string", length=191, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=5000, nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cover;
    

    /**
     * @Vich\UploadableField(mapping="blog_image", fileNameProperty="cover")
     * @var File|null
    */
    private  $coverFile = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible = false;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     *
     * @ORM\JoinColumns({
     *
     *   @ORM\JoinColumn(name="clubId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $clubid;

    /**
     * @var \Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sectionId", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $sectionid;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=191, nullable=true)
     */
    private $video;


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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function __ToString(): string {
        return $this->title;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCoverFile(): ?File
    {
        return $this->coverFile;
    }

    public function setCoverFile(?File $coverFile): void
    {
        $this->coverFile = $coverFile;

        if ($coverFile instanceof UploadedFile) {
            $this->updatedat = new \DateTime();
        }
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

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function serialize()
    {
        $this->coverFile = base64_encode($this->coverFile);
    }

    public function unserialize($serialized)
    {
        $this->coverFile = base64_decode($this->coverFile);

    }
    
    public function getVideo() {
        return $this->video;
    }

    public function setVideo($video) {
        $this->video = $video;
    }

}
