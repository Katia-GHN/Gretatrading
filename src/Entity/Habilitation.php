<?php

namespace App\Entity;

use App\Repository\HabilitationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabilitationRepository::class)]
class Habilitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $niveau = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\OneToMany(mappedBy: 'lahabilitation', targetEntity: Trader::class)]
    private Collection $lestraders;


    public function __construct()
    {
        $this->lahabilitation = new ArrayCollection();
        $this->laHabilitation = new ArrayCollection();
        $this->lestraders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }


    /* ******** LES Traders *******/

    /**
     * @return Collection<int, Trader>
     */
    public function getLestraders(): Collection
    {
        return $this->lestraders;
    }

    public function addLestrader(Trader $lestrader): static
    {
        if (!$this->lestraders->contains($lestrader)) {
            $this->lestraders->add($lestrader);
            $lestrader->setLahabilitation($this);
        }

        return $this;
    }

    public function removeLestrader(Trader $lestrader): static
    {
        if ($this->lestraders->removeElement($lestrader)) {
            // set the owning side to null (unless already changed)
            if ($lestrader->getLahabilitation() === $this) {
                $lestrader->setLahabilitation(null);
            }
        }

        return $this;
    }

}
