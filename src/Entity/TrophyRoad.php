<?php

namespace App\Entity;

use App\Enum\DataStatut;
use App\Enum\RoadType;
use App\Repository\TrophyRoadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TrophyRoadRepository::class)]
#[ORM\HasLifecycleCallbacks]
class TrophyRoad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['trophyRoad', 'all'])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['trophyRoad'])]
    #[ORM\Column(type: 'string', length: 255, enumType: DataStatut::class)]
    private DataStatut $statut;

    #[Groups(['trophyRoad'])]
    #[ORM\Column(type: 'string', length: 255, enumType: RoadType::class, nullable:True)]
    private ?RoadType $type = null;

    #[Groups(['trophyRoad'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[Groups(['trophyRoad'])]
    #[ORM\ManyToOne(inversedBy: 'trophyRoads')]
    private ?Project $project = null;

    #[Groups(['trophyRoad'])]
    #[ORM\OneToMany(targetEntity: Trophy::class, mappedBy: 'trophyRoad')]
    private Collection $trophies;

    #[Groups(['trophyRoad'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['trophyRoad'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->statut = DataStatut::ACTIF;
        $this->type = RoadType::MAIN;
        $this->trophies = new ArrayCollection();
    }

    public function __toString() : string {
        return "#" . $this->getId() . " "
            . ($this->getType() != null ? "(" . $this->getType()->value . ")": "")
            . ($this->getName() != null ? " --- " . $this->getName() : "")
            . ($this->getProject() != null ? " --- " . $this->getProject()->getTitle() : "")
        ;
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

    function getType() : ?RoadType {
        return $this->type;
    }

    function setType(?RoadType $type) : self {
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

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
            $trophy->setTrophyRoad($this);
        }

        return $this;
    }

    public function removeTrophy(Trophy $trophy): static
    {
        if ($this->trophies->removeElement($trophy)) {
            // set the owning side to null (unless already changed)
            if ($trophy->getTrophyRoad() === $this) {
                $trophy->setTrophyRoad(null);
            }
        }

        return $this;
    }

    public function getTrophiesCount(): int
    {
        return $this->trophies->count();
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
