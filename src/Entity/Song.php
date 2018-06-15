<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="song")
 */
class Song
{
    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="songs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\Column(name="performer", type="string")
     * @Assert\Expression(
     *     "this.getPerformer() or this.getTitle()",
     *     message="song_empty"
     * )
     */
    private $performer = '';

    /**
     * @ORM\Column(name="title", type="string")
     */
    private $title = '';

    /**
     * @ORM\Column(name="status", type="string")
     */
    private $status = self::STATUS_NEW;

    /**
     * Constructor
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Get performer
     */
    public function getPerformer(): string
    {
        return $this->performer;
    }
    
    /**
     * Set performer
     */
    public function setPerformer(string $performer): self
    {
        $this->performer = $performer;
        return $this;
    }

    /**
     * Get title
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * Set title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Approve this song
     */
    public function approve(): self
    {
        $this->status = self::STATUS_APPROVED;
        return $this;
    }

    /**
     * Reject this song
     */
    public function reject(): self
    {
        $this->status = self::STATUS_REJECTED;
        return $this;
    }
}
