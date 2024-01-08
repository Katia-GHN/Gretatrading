<?php

namespace App\Entity;

use App\Repository\CoursActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursActionRepository::class)]
class CoursAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datecoursaction = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'lescoursactions')]
    private ?Action $laaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecoursaction(): ?\DateTimeInterface
    {
        return $this->datecoursaction;
    }

    public function setDatecoursaction(\DateTimeInterface $datecoursaction): static
    {
        $this->datecoursaction = $datecoursaction;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLaaction(): ?Action
    {
        return $this->laaction;
    }

    public function setLaaction(?Action $laaction): static
    {
        $this->laaction = $laaction;

        return $this;
    }

    // Calcule la variation en pourcentage du prix de l'action par rapport à la veille.
    public function calculerVariationJournaliere (): ?float
    {
        // Prix aujourdhui et prix hier
        $dateHier = (clone $this->datecoursaction)->modify('-1 day');

        
        $coursHier = $this->laaction->getCoursActionValide($dateHier);
        
        if ($coursHier === null || $coursHier == 0) {
            return null;
        }
        return (($this->prix - $coursHier) / $coursHier) * 100;
    }

    //Récupère le prix le plus élevé jamais atteint par l'action. (voir classe Action)
    public function getPlusHautPrixHistorique (): bool 
    {
        return $this->laaction->getCoursMax() == $this->prix;
    }

    //Compare le prix actuel de l'action avec sa moyenne mobile sur un certain nombre de jours.
    public function compareAvecMoyenneMobile( int $param): bool
    {     
        $dateDebut = (clone $this->datecoursaction)->modify('-'. $param.'day');
        //Booléen indiquant si le prix actuel est au-dessus ou en dessous de la moyenne mobile.
        return $this->laaction->getMoyenMobile() == $this->prix;
    }

}
