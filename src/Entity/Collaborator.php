<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Repository\CollaboratorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CollaboratorRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Collaborator
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
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[Groups(['export'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(['export'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(['export'])]
    #[Vich\UploadableField(mapping: 'collaborators_illustration', fileNameProperty: 'illustrationName')]
    private ?File $illustrationFile = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?string $illustrationName = null;

    #[Groups(['export'])]
    #[Vich\UploadableField(mapping: 'collaborators_profile', fileNameProperty: 'profileName')]
    private ?File $profileFile = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?string $profileName = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['export'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['export'])]
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'collaborators')]
    private Collection $projects;

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

    public function __toString(): string
    {
        return $this->getName() . " " . $this->getSurname();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    // Illustration au format carte
    public function getIllustrationFile(): ?File
    {
        return $this->illustrationFile;
    }

    public function setIllustrationFile(?File $illustrationFile): self {
        $this->illustrationFile = $illustrationFile;
        if ($illustrationFile) {
            $this->illustrationName = $illustrationFile->getFilename();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getIllustrationName(): ?string
    {
        return $this->illustrationName;
    }

    public function setIllustrationName(?string $illustrationName): void
    {
        $this->illustrationName = $illustrationName;
    }

    // Photo de profile
    public function getProfileFile(): ?File
    {
        return $this->profileFile;
    }

    public function setProfileFile(?File $profileFile): self {
        $this->profileFile = $profileFile;
        if ($profileFile) {
            $this->profileName = $profileFile->getFilename();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    public function setProfileName(?string $profileName): void
    {
        $this->profileName = $profileName;
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
            $project->addCollaborator($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeCollaborator($this);
        }

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
