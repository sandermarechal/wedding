<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="usr")
 * @UniqueEntity(fields="email", message="email_taken")
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
     * @ORM\Column(name="name", type="string")
     * @Assert\NotBlank()
     */
    private $name = '';

    /**
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email = '';

    /**
     * @ORM\Column(name="locale", type="string", length=2)
     * @Assert\NotBlank()
     */
    private $locale = '';

    /**
     * @ORM\Column(name="roles", type="simple_array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(name="password", type="string")
     */
    private $password = '';

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max=4096, groups={"registration"})
     */
    private $plainPassword = '';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Guest", mappedBy="user")
     */
    private $guests;

    /**
     * Constructor
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
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
     * Getter for locale
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Set locale
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
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
     * Getter for plainPassword
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
    
    /**
     * Setter for plainPassword
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = '';
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
     *
     * @return array
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    /**
     * Unserialize the user
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }
}
