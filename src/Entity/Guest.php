<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Rsvp;
use Doctrine\ORM\Mapping as ORM;

/**
 * Guest
 *
 * @ORM\Entity(repositoryClass="App\Repository\GuestRepository")
 * @ORM\Table(name="guest")
 */
class Guest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="guests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\Column(name="ceremony", type="string")
     */
    private $ceremony = Rsvp::MAYBE;

    /**
     * @ORM\Column(name="party", type="string")
     */
    private $party = Rsvp::MAYBE;

    /**
     * Constructor
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Getter for id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Getter for name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Setter for name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Getter for email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * Setter for email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Getter for user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    /**
     * Setter for user
     */
    public function setUser(User $user = null): self
    {
        if ($this->user == $user) {
            return $this;
        }
    
        $old = $this->user;
        $this->user = $user;
    
        if ($old !== null) {
            $old->removeGuest($this);
        }
    
        if ($user !== null) {
            $user->addGuest($this);
        }
    
        return $this;
    }

    /**
     * Get ceremony RSVP
     */
    public function getCeremony(): ?string
    {
        return $this->ceremony;
    }
    
    /**
     * Set ceremony RSVP
     */
    public function setCeremony(?string $ceremony): self
    {
        $this->ceremony = $ceremony;
        return $this;
    }

    /**
     * Get party RSVP
     */
    public function getParty(): ?string
    {
        return $this->party;
    }
    
    /**
     * Set party RSVP
     */
    public function setParty(?string $party): self
    {
        $this->party = $party;
        return $this;
    }
}
