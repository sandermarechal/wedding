<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="usr")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="roles", type="simple_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Guest", mappedBy="user")
     */
    private $guests;

    /**
     * Constructor
     */
    public function __construct(string $email)
    {
        $this->email = $email;
        $this->guests = new ArrayCollection();
    }

    /**
     * Getter for id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the username used to authenticate the user.
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * Getter for email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Setter for email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the encoded password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * No salt required, it is part of the encoded password
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Getter for guests
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }
    
    /**
     * Add guest
     */
    public function addGuest(Guest $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests[] = $guest;
            $guest->setUser($this);
        }
    
        return $this;
    }
    
    /**
     * Remove guest
     */
    public function removeGuest(Guest $guest): self
    {
        if ($this->guests->removeElement($guest)) {
            $guest->setUser(null);
        }
    
        return $this;
    }

    /**
     * Serialize the user
     */
    public function serialize(): array
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /**
     * Unserialize the user
     */
    public function unserialize(string $serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }
}
