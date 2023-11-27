<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Float_;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetransaction = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lestransactions')]
    private ?Trader $letrader = null;

    #[ORM\ManyToOne(inversedBy: 'lestransaction')]
    private ?Action $laAction = null;

    #[ORM\Column(length: 255)]
    private ?string $Operation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetransaction(): ?\DateTimeInterface
    {
        return $this->datetransaction;
    }

    public function setDatetransaction(\DateTimeInterface $datetransaction): static
    {
        $this->datetransaction = $datetransaction;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getLaAction(): ?Action
    {
        return $this->laAction;
    }

    public function setLaAction(?Action $laAction): static
    {
        $this->laAction = $laAction;

        return $this;
    }

    public function getOperation(): ?string
    {
        return $this->Operation;
    }

    public function setOperation(string $Operation): static
    {
        $this->Operation = $Operation;

        return $this;
    }
// Calculer le prix moyen d'achat ou de vente basé sur les transactions passées. 
//Cela pourrait impliquer de séparer les transactions en achats et ventes pour cette action.
    
    public function getcoursTransaction(): ?float // ? Pour dire qu'il accepte le null (le rien)
    {   
        $coursAuPlusProche = 0.00;
        // je vais voir toutes les transactions de la coursaction


        // Si il n'y a pas action retour null
        if (!$this->laAction) {
            return null; // Aucune action associée à cette transaction
        }
        else 
        {
            $coursAuPlusProche = $this->laAction->getPrix();
        }
        // dans transaction on a une action qui correspond à une collection coursactions (=porte)
        foreach ($this->laAction->getLescoursactions() as $coursAction)
        {   // DATE DE LA TRANSACTION = Date de CoursAction donc return
            if ($coursAction->getDatecoursaction()->format('Y-m-d') === $this->datetransaction->format('Y-m-d'))
            {
                return $coursAction->getPrix();
            }


        }
        return null;

        // chaque action a une collection de coursaction
    }
    public function getcoursTransactionAuPlusProche(): ?float // ? Pour dire qu'il accepte le null (le rien)
    {   
        $coursAuPlusProche = 0.00;
        // je vais voir toutes les transactions de la coursaction


        // Si il n'y a pas action retour null
        if (!$this->laAction) {
            return null; // Aucune action associée à cette transaction
        }
        // dans transaction on a une action qui correspond à une collection coursactions (=porte)
        foreach ($this->laAction->getLescoursactions() as $coursAction)
        {   // DATE DE LA TRANSACTION = Date de CoursAction donc return
            if ($coursAction->getDatecoursaction()->format('Y-m-d') === $this->datetransaction->format('Y-m-d'))
            {
                return $coursAction->getPrix(); // SI OUI RENVOIE LE PRIX
            }
            elseif($coursAction->getDatecoursaction() > $this->datetransaction ) // SINON SI 
            {
                $coursAuPlusProche = $coursAction->getPrix();
            }
            else // Convention : avec un elseif on fini toujours une fonction avec un else
    
            {
                $coursAuPlusProche = $coursAction->getPrix();
            }

        }
        return $coursAuPlusProche;
        // chaque action a une collection de coursaction
    }
  
}
