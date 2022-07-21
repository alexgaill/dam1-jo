<?php

namespace App\Entity;

use App\Repository\DisciplineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisciplineRepository::class)]
class Discipline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'discipline', targetEntity: Athlete::class, orphanRemoval: true)]
    private Collection $athletes;

    public function __construct()
    {
        $this->athletes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Athlete>
     */
    public function getAthletes(): Collection
    {
        return $this->athletes;
    }

    public function addAthlete(Athlete $athlete): self
    {
        if (!$this->athletes->contains($athlete)) {
            $this->athletes[] = $athlete;
            $athlete->setDiscipline($this);
        }

        return $this;
    }

    public function removeAthlete(Athlete $athlete): self
    {
        if ($this->athletes->removeElement($athlete)) {
            // set the owning side to null (unless already changed)
            if ($athlete->getDiscipline() === $this) {
                $athlete->setDiscipline(null);
            }
        }

        return $this;
    }
}
