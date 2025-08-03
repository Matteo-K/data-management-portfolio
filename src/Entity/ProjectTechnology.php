<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Repository\ProjectTechnologyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectTechnologyRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProjectTechnology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, enumType: DataStatut::class)]
    private DataStatut $statut;

    #[ORM\ManyToOne(inversedBy: 'projectTechnologies')]
    private ?Project $project = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Technology $technologie = null;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentage_using = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __toString(): string
    {
        return $this->getPourcentageUsing() . '% de ' . ($this->getTechnologie()->__toString() ?? 'Technologie anonyme');
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    function getStatut() : DataStatut {
        return $this->statut;
    }

    function setStatut(DataStatut $statut) : self {
        $this->statut = $statut;
        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getTechnologie(): ?Technology
    {
        return $this->technologie;
    }

    public function setTechnologie(?Technology $technologie): static
    {
        $this->technologie = $technologie;

        return $this;
    }

    public function getPourcentageUsing(): ?float
    {
        return $this->pourcentage_using;
    }

    public function setPourcentageUsing(?float $pourcentage_using): static
    {
        $this->pourcentage_using = $pourcentage_using;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
