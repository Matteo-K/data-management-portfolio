<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class School
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['export'])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['export'])]
    #[ORM\Column(type: 'string', length: 255, enumType: DataStatut::class)]
    private DataStatut $statut;

    #[Groups(['export'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['export'])]
    #[ORM\Column(length: 255)]
    private ?string $link_web = null;

    #[Groups(['export'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(['export'])]
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'school', cascade: ['persist', 'remove'])]
    private Collection $projects;

    #[Groups(['export'])]
    #[Vich\UploadableField(mapping: 'school_illustration', fileNameProperty: 'logoName')]
    private ?File $logoFile = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?string $logoName = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->statut = DataStatut::ACTIF;
        $this->projects = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->getName();
    }

    function getStatut() : DataStatut {
        return $this->statut;
    }

    function setStatut(DataStatut $statut) : self {
        $this->statut = $statut;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLinkWeb(): ?string
    {
        return $this->link_web;
    }

    public function setLinkWeb(string $link_web): static
    {
        $this->link_web = $link_web;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
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
            $project->setSociety($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getSociety() === $this) {
                $project->setSociety(null);
            }
        }

        return $this;
    }

    // Logo de la société
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoFile(?File $logoFile): self {
        $this->logoFile = $logoFile;
        if ($logoFile) {
            $this->logoName = $logoFile->getFilename();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setLogoName(?string $logoName): void
    {
        $this->logoName = $logoName;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
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
