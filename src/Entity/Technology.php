<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Technology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['technology', 'all'])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['technology'])]
    #[ORM\Column(type: 'string', length: 255, enumType: DataStatut::class)]
    private DataStatut $statut;

    #[Groups(['technology'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'technology_illustration', fileNameProperty: 'illustrationName')]
    private ?File $illustrationFile = null;

    #[Groups(['technology'])]
    #[ORM\Column(nullable: true)]
    private ?string $illustrationName = null;

    #[Groups(['technology'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date = null;

    #[Groups(['technology'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['technology'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['technology'])]
    #[ORM\Column(nullable: false)]
    private int $priority;

    public function __construct() {
        $this->priority = 0;
        $this->statut = DataStatut::ACTIF;
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

    public function getIllustrationFile(): ?File
    {
        return $this->illustrationFile;
    }

    public function setIllustrationFile(?File $illustrationFile): self
    {
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }
}
