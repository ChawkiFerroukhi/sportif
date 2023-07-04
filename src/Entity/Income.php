<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Income
 *
 * @ORM\Table(name="Income", indexes={})
 * @ORM\Entity
 */
class Income extends Decaissement
{
    protected $discr = 'income';

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
    protected $type = 'income';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $userid;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getType():string 
    {
        return $this->type;
    }

    public function __ToString(): string {
        return $this->nom;
    }

    

}
