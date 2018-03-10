<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Guest
 *
 * @ORM\Entity
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
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Setter for email
     *
     * @param sting|null $email
     */
    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Getter for user
     *
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Setter for user
     *
     * @param User $user
     * @return self
     */
    public function setUser(User $user = null)
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
}