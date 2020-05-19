<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
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
     * @ORM\OneToMany(targetEntity=Tool::class, mappedBy="department")
     */
    private $Tools;

    /**
     * @ORM\OneToMany(targetEntity=Workstation::class, mappedBy="department")
     */
    private $workstations;

    /**
     * @ORM\Column(type="integer")
     */
    private $code;

    public function __construct()
    {
        $this->Tools = new ArrayCollection();
        $this->workstations = new ArrayCollection();
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
            $tool->setDepartment($this);
        }

        return $this;
    }

    public function removeTool(Tool $tool): self
    {
        if ($this->Tools->contains($tool)) {
            $this->Tools->removeElement($tool);
            // set the owning side to null (unless already changed)
            if ($tool->getDepartment() === $this) {
                $tool->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Workstation[]
     */
    public function getWorkstations(): Collection
    {
        return $this->workstations;
    }

    public function addWorkstation(Workstation $workstation): self
    {
        if (!$this->workstations->contains($workstation)) {
            $this->workstations[] = $workstation;
            $workstation->setDepartment($this);
        }

        return $this;
    }

    public function removeWorkstation(Workstation $workstation): self
    {
        if ($this->workstations->contains($workstation)) {
            $this->workstations->removeElement($workstation);
            // set the owning side to null (unless already changed)
            if ($workstation->getDepartment() === $this) {
                $workstation->setDepartment(null);
            }
        }

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFullName(): ?string
    {
        return strval($this->getCode()).' - '.$this->getName();
    }
}
