<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PaysRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;

#[
    ORM\Entity(repositoryClass: PaysRepository::class),
    ORM\HasLifecycleCallbacks
]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $nom = null;

    #[ORM\Column(length: 40)]
    private ?string $drapeau = null;

    #[ORM\OneToMany(mappedBy: 'pays', targetEntity: Athlete::class, orphanRemoval: true)]
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

    public function getDrapeau(): string|File|null
    {
        return $this->drapeau;
    }

    public function setDrapeau(string|File|null $drapeau): self
    {
        $this->drapeau = $drapeau;

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
            $athlete->setPays($this);
        }

        return $this;
    }

    public function removeAthlete(Athlete $athlete): self
    {
        if ($this->athletes->removeElement($athlete)) {
            // set the owning side to null (unless already changed)
            if ($athlete->getPays() === $this) {
                $athlete->setPays(null);
            }
        }

        return $this;
    }

    #[ORM\PostRemove]
    public function deleteDrapeau()
    {
        if(file_exists(__DIR__ . "/../../public/assets/img/upload/flags/". $this->drapeau)) {
            unlink(__DIR__ . "/../../public/assets/img/upload/flags/". $this->drapeau);
        }
    }
}
