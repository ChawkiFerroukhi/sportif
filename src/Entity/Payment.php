<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="Payment", indexes={})
 * @ORM\Entity
 */
class Payment extends Encaissement
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=191, nullable=false)
     */
    protected $type = 'paiement';

    /**
     * @var \Adherant
     *
     * @ORM\ManyToOne(targetEntity="Adherant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adherantid", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $adherantid;

    public function getId(): ?int
    {
        return $this->id;
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

    public function __ToString(): string {
        return $this->nom;
    }

    

}
