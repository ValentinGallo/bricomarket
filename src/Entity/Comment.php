<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Tool::class, inversedBy="comments")
     */
    private $Tool;

    /**
     * @ORM\ManyToOne(targetEntity=Workstation::class, inversedBy="comments")
     */
    private $Workstation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getTool(): ?Tool
    {
        return $this->Tool;
    }

    public function setTool(?Tool $Tool): self
    {
        $this->Tool = $Tool;

        return $this;
    }

    public function getWorkstation(): ?Workstation
    {
        return $this->Workstation;
    }

    public function setWorkstation(?Workstation $Workstation): self
    {
        $this->Workstation = $Workstation;

        return $this;
    }
}
