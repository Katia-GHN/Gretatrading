<?php

namespace App\Entity;

use App\Repository\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraderRepository::class)]
class Trader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'letrader', targetEntity: Transaction::class)]
    private Collection $lestransactions;

    public function __construct()
    {
        $this->lestransactions = new ArrayCollection();
    }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getLestransactions(): Collection
    {
        return $this->lestransactions;
    }

    public function addLestransaction(Transaction $lestransaction): static
    {
        if (!$this->lestransactions->contains($lestransaction)) {
            $this->lestransactions->add($lestransaction);
            $lestransaction->setLetrader($this);
        }

        return $this;
    }

    public function removeLestransaction(Transaction $lestransaction): static
    {
        if ($this->lestransactions->removeElement($lestransaction)) {
            // set the owning side to null (unless already changed)
            if ($lestransaction->getLetrader() === $this) {
                $lestransaction->setLetrader(null);
            }
        }

        return $this;
    }

    public function GetHistoriqueTransaction():Collection

    {
        return $this->lestransactions;
    }

    // Quantite d'actions detenu par un Trader

    public function getDiversificationPortfolio() : array
    { 
        $Portfolio = [];

        foreach ($this->lestransactions as $laTransaction)
            {
                if (array_key_exists($laTransaction->getLaaction()->getNom(), $Portfolio)) // est-ce que ce nom existe dans portfolio
                {
                    if ($laTransaction->getOperation() === "Achat")
                    {
                        $Portfolio[$laTransaction->getLaaction()->getNom()]+= $laTransaction->getQuantite();
                    }
                    else
                    {
                        $Portfolio[$laTransaction->getLaaction()->getNom()]-= $laTransaction->getQuantite();
                    }
                }
                else
                {
                    $Portfolio[$laTransaction->getLaaction()->getNom()] = $laTransaction->getQuantite();
                }
                
            }

        return $Portfolio;
    }

    public function genereCrypt(string $motacoder, int $cle): string
    {
    $alphabet = range("a", "z");
    $motcrypte = "";

    // Utilisation de strlen au lieu de strlens
    for ($i = 0; $i < strlen($motacoder); $i++) {
        $caractere = $motacoder[$i];

        // Vérifier si le caractère est une lettre
        if (ctype_alpha($caractere)) {
            // Trouver la position de la lettre dans l'alphabet
            $position = array_search($caractere, $alphabet);

            // Appliquer le décalage
            $nouvellePosition = ($position + $cle) % 26;
            $caractere = $alphabet[$nouvellePosition];
        }

        // Ajouter le caractère au résultat (correction de l'erreur ici)
        $motcrypte .= $caractere;
    }

    return $motcrypte;
    }

    public function decrypterSansCle (string $motadecoder) : collection
    {

    }

}