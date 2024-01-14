<?php

namespace App\Entity;

use App\Repository\MotdepasseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotdepasseRepository::class)]
class Motdepasse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'lesmotsdepasse')]
    private ?Trader $letrader = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLetrader(): ?Trader
    {
        return $this->letrader;
    }

    public function setLetrader(?Trader $letrader): static
    {
        $this->letrader = $letrader;

        return $this;
    }

    private function verifierMdp(): bool
    {
        $controle = false;

        $minuscule = preg_match_all('/[a-z]' , $nom);
        $majuscule = preg_match_all('/[A-Z]' , $nom);
        $chiffre = preg_match_all('/\d/', $nom); // \d remplace le [0-9]
        $specialcaract = preg_match_all('/\W/');
        $longueur = strlen($nom); // longueur du mot de passe

        if ( $minuscule > 3 && $majuscule > 1 && $chiffre > 4 && $specialcaract > 1 && $longueur > 12 )
        {
            return true;
        }

        return $controle;

        // preg_match_all équivalent du len (compte le nombre dans un interval )
    }



    
}
