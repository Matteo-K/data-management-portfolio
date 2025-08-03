<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Enum\ProjectObjective;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, enumType: DataStatut::class)]
    private DataStatut $statut;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: ProjectTechnology::class, mappedBy: 'project')]
    private Collection $projectTechnologies;

    #[ORM\Column(type: 'string', length: 255, enumType: ProjectObjective::class)]
    private ProjectObjective $objective;

    #[ORM\ManyToOne(inversedBy: 'projects', cascade: ['persist'])]
    private ?Society $society = null;

    #[ORM\ManyToOne(inversedBy: 'projects', cascade: ['persist'])]
    private ?School $school = null;

    #[ORM\OneToMany(targetEntity: Trophy::class, mappedBy: 'project')]
    private Collection $trophies;

    #[Vich\UploadableField(mapping: 'projects_illustration_card', fileNameProperty: 'illustrationCardName')]
    private ?File $illustrationCardFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $illustrationCardName = null;

    #[Vich\UploadableField(mapping: 'projects_illustration_background', fileNameProperty: 'illustrationBackgroundName')]
    private ?File $illustrationBackgroundFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $illustrationBackgroundName = null;

    #[Vich\UploadableField(mapping: 'projects_illustration_title', fileNameProperty: 'illustrationTitleName')]
    private ?File $illustrationTitleFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $illustrationTitleName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date = null;

    // Liens
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $web = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $github = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Collaborator::class, inversedBy: 'projects')]
    private Collection $collaborators;

    public function __construct()
    {
        $this->projectTechnologies = new ArrayCollection();
        $this->trophies = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getTitle();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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
     * @return Collection<int, ProjectTechnology>
     */
    public function getProjectTechnologies(): Collection
    {
        return $this->projectTechnologies;
    }

    public function addProjectTechnology(ProjectTechnology $projectTechnology): static
    {
        if (!$this->projectTechnologies->contains($projectTechnology)) {
            $this->projectTechnologies->add($projectTechnology);
            $projectTechnology->setProject($this);
        }

        return $this;
    }

    public function removeProjectTechnology(ProjectTechnology $projectTechnology): static
    {
        if ($this->projectTechnologies->removeElement($projectTechnology)) {
            // set the owning side to null (unless already changed)
            if ($projectTechnology->getProject() === $this) {
                $projectTechnology->setProject(null);
            }
        }

        return $this;
    }

    function getObjective() : ProjectObjective {
        return $this->objective;
    }

    function setObjective(ProjectObjective $objective) : self {
        $this->objective = $objective;
        return $this;
    }

    public function getSociety(): ?Society
    {
        return $this->society;
    }

    public function setSociety(?Society $society): static
    {
        $this->society = $society;

        return $this;
    }
    
    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection<int, Trophy>
     */
    public function getTrophies(): Collection
    {
        return $this->trophies;
    }

    public function addTrophy(Trophy $trophy): static
    {
        if (!$this->trophies->contains($trophy)) {
            $this->trophies->add($trophy);
            $trophy->setProject($this);
        }

        return $this;
    }

    public function removeTrophy(Trophy $trophy): static
    {
        if ($this->trophies->removeElement($trophy)) {
            // set the owning side to null (unless already changed)
            if ($trophy->getProject() === $this) {
                $trophy->setProject(null);
            }
        }

        return $this;
    }

    // Illusatration pour la carte
    public function getIllustrationCardFile(): ?File
    {
        return $this->illustrationCardFile;
    }

    public function setIllustrationCardFile(?File $illustrationCardFile): self {
        $this->illustrationCardFile = $illustrationCardFile;
        if ($illustrationCardFile) {
            $this->illustrationCardName = $illustrationCardFile->getFilename();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function setIllustrationCardName(?string $illustrationCardName): void
    {
        $this->illustrationCardName = $illustrationCardName;
    }

    public function getIllustrationCardName(): ?string
    {
        return $this->illustrationCardName;
    }

    // Illusatration pour le background
    public function getIllustrationBackgroundFile(): ?File
    {
        return $this->illustrationBackgroundFile;
    }

    public function setIllustrationBackgroundFile(?File $illustrationBackgroundFile): self {
        $this->illustrationBackgroundFile = $illustrationBackgroundFile;
        if ($illustrationBackgroundFile) {
            $this->illustrationBackgroundName = $illustrationBackgroundFile->getFilename();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getIllustrationBackgroundName(): ?string
    {
        return $this->illustrationBackgroundName;
    }

    public function setIllustrationBackgroundName(?string $illustrationBackgroundName): void
    {
        $this->illustrationBackgroundName = $illustrationBackgroundName;
    }

    // Titre designé
    public function getIllustrationTitleFile(): ?File
    {
        return $this->illustrationTitleFile;
    }

    public function setIllustrationTitleFile(?File $illustrationTitleFile): self {
        $this->illustrationTitleFile = $illustrationTitleFile;
        if ($illustrationTitleFile) {
            $this->illustrationTitleName = $illustrationTitleFile->getFilename();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getIllustrationTitleName(): ?string
    {
        return $this->illustrationTitleName;
    }

    public function setIllustrationTitleName(?string $illustrationTitleName): void
    {
        $this->illustrationTitleName = $illustrationTitleName;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): static
    {
        $this->web = $web;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): static
    {
        $this->github = $github;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
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

    /**
     * @return Collection<int, Collaborator>
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function addCollaborator(Collaborator $collaborator): static
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }

        return $this;
    }

    public function removeCollaborator(Collaborator $collaborator): static
    {
        $this->collaborators->removeElement($collaborator);

        return $this;
    }
}
