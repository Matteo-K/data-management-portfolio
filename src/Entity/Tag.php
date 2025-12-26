<?php

namespace App\Entity;

use App\Repository\TagRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['tag', 'all'])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['tag'])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $label = null;

    #[Groups(['tag'])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $completeLabel = null;

    #[Groups(['tag'])]
    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[Groups(['tag'])]
    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Project>
     */
    #[Groups(['tag'])]
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'tags')]
    private Collection $projects;

    public function __construct()
    {
        $this->Project = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getCompleteLabel(): ?string
    {
        return $this->completeLabel;
    }

    public function setCompleteLabel(string $completeLabel): static
    {
        $this->completeLabel = $completeLabel;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addTag($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeTag($this);
        }

        return $this;
    }
}
