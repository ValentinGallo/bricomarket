<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Tool::class, mappedBy="category")
     */
    private $Tools;

    public function __construct()
    {
        $this->Tools = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Tool[]
     */
    public function getTools(): Collection
    {
        return $this->Tools;
    }

    public function addTool(Tool $tool): self
    {
        if (!$this->Tools->contains($tool)) {
            $this->Tools[] = $tool;
            $tool->setCategory($this);
        }

        return $this;
    }

    public function removeTool(Tool $tool): self
    {
        if ($this->Tools->contains($tool)) {
            $this->Tools->removeElement($tool);
            // set the owning side to null (unless already changed)
            if ($tool->getCategory() === $this) {
                $tool->setCategory(null);
            }
        }

        return $this;
    }
}
