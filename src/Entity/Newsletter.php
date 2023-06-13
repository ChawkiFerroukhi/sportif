<?php

namespace App\Entity;

use DateTimeInterface;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Newsletter {
    private $subject;
    private $body;
    public function __construct() {

    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }
    public function getBody(): ?string
    {
        return $this->body;
    }
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}