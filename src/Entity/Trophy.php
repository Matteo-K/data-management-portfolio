<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Enum\TrophyType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrophyRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TrophyRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Trophy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, enumType: DataStatut::class)]
    private DataStatut $statut;

    #[ORM\Column(type: 'string', length: 255, enumType: TrophyType::class)]
    private TrophyType $type;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'trophy_illustration', fileNameProperty: 'illustrationName')]
    private ?File $illustrationFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $illustrationName = null;

    #[ORM\Column]
    private ?bool $accomplished = null;

    #[ORM\ManyToOne(inversedBy: 'trophies')]
    private ?Project $project = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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
        $project = $this->getProject();
        return ($project != null ? $project->__toString() : "ID " . $this->getId()) . " : \"" . $this->getName() . "\"";
    }

    function getStatut() : DataStatut {
        return $this->statut;
    }

    function setStatut(DataStatut $statut) : self {
        $this->statut = $statut;
        return $this;
    }

    function getType() : TrophyType {
        return $this->type;
    }

    function setType(TrophyType $type) : self {
        $this->type = $type;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

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

    public function isAccomplished(): ?bool
    {
        return $this->accomplished;
    }

    public function setAccomplished(bool $accomplished): static
    {
        $this->accomplished = $accomplished;

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
