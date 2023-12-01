<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use App\Repository\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'laAction', targetEntity: Transaction::class)]
    private Collection $lestransaction;

    #[ORM\OneToMany(mappedBy: 'laaction', targetEntity: CoursAction::class)]
    private Collection $lescoursactions;

    public function __construct()
    {
        $this->lestransaction = new ArrayCollection();
        $this->lescoursactions = new ArrayCollection();
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getLestransaction(): Collection
    {
        return $this->lestransaction;
    }

    public function addLestransaction(Transaction $lestransaction): static
    {
        if (!$this->lestransaction->contains($lestransaction)) {
            $this->lestransaction->add($lestransaction);
            $lestransaction->setLaAction($this);
        }

        return $this;
    }

    public function removeLestransaction(Transaction $lestransaction): static
    {
        if ($this->lestransaction->removeElement($lestransaction)) {
            // set the owning side to null (unless already changed)
            if ($lestransaction->getLaAction() === $this) {
                $lestransaction->setLaAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CoursAction>
     */
    public function getLescoursactions(): Collection
    {
        return $this->lescoursactions;
    }

    public function addLescoursaction(CoursAction $lescoursaction): static
    {
        if (!$this->lescoursactions->contains($lescoursaction)) {
            $this->lescoursactions->add($lescoursaction);
            $lescoursaction->setLaaction($this);
        }

        return $this;
    }

    public function removeLescoursaction(CoursAction $lescoursaction): static
    {
        if ($this->lescoursactions->removeElement($lescoursaction)) {
            // set the owning side to null (unless already changed)
            if ($lescoursaction->getLaaction() === $this) {
                $lescoursaction->setLaaction(null);
            }
        }

        return $this;
    }

    public function calculerCoursMoyen(): float
    {
        $coursAction = $this->getLescoursactions();
        $nombreCours = count ($coursAction);

        if($nombreCours === 0) {
            return 0.0; // FLOAT qu'on doit préciser pour pas avoir d'erreur
        }

        $totalPrix = 0; 
        foreach ($coursAction as $cours) {
            $totalPrix += $cours -> getPrix();
        }

        return $totalPrix / $nombreCours;
    }
// Retourner une methode 
    public function GetDetenteursActionLast(): Trader
    {
        $unTrader = null;
        foreach($this->getLestransaction() as $laTransaction)
        {
            $unTrader = $laTransaction->GetLetrader();
        }

        return $unTrader;
    }
    // Methode pour obtenir la derniere action d'achat d'un trader
    public function GetDetenteursActionLastAchat(): Trader
    {
        $unTrader = null;
        foreach($this->getLestransaction() as $laTransaction)
        {
            if($laTransaction->getOperation() == "Achat")
            {
            $unTrader = $laTransaction->GetLetrader();
            }
        }

        return $unTrader;
       
    }
    // ON VEUT OBTENIR UNE LISTE D ACHETEURS 
    public function GetAcheteurs(): Collection
    {
        $lesacheteurs = new ArrayCollection();
        foreach($this->getLestransaction() as $laTransaction) {
            if ($laTransaction -> getOperation() == "Achat") {
                $acheteur = $laTransaction-> getLetrader();
                if (!$lesacheteurs->contains($acheteur)) {
                    $lesacheteurs->add($acheteur);
                }
                
            }
        }
        return $lesacheteurs;
    }
    //1 Methode pour avoir que les traders qui ont encore des actions
    // on creer un dico donc array key exists
    public function GetAcheteursDetenteurs(traderRepository $traderRepository): Collection
    {
        $lesacheteursDetenteurs = new ArrayCollection();
        $monDictionnaireAcheteursDetenteurs = [];

        foreach($this->getLestransaction() as $laTransaction) 
        {
            $trader = $laTransaction->getLetrader();
            $quantite = $laTransaction ->getQuantite();
            $operation = $laTransaction->getOperation();

            if (!array_key_exists($trader->getId(),$monDictionnaireAcheteursDetenteurs))

            if ($laTransaction-> getOperation() == "Achat") 
            {
                $monDictionnaireAcheteursDetenteurs[$trader->getId()] += $quantite;
            }
            elseif ($laTransaction->getOperation() == "Vente")
            {
                $monDictionnaireAcheteursDetenteurs[$trader->getId()] -= $quantite;
            }
        }
        
        foreach ($monDictionnaireAcheteursDetenteurs as $traderId => $quantite) {
            if($quantite >0) {
                $trader = $traderRepository ->find($traderId);
                $lesacheteursDetenteurs->add($trader);
            }
        } 
        return $lesacheteursDetenteurs;
    }

    //2 Une méthode pour récupérer le dernier prix enregistré dans CoursAction pour cette action. 
    // Cela fournirait le prix actuel de l'action.

    public function GetDernierPrixAction(): float
    {
        $resultat = 0.0;
        foreach ($this->lescoursactions as $leCoursAction) // Balaye dans collection $lescoursaction this passerelle avec la classe COURSACTION 
        {
         $resultat = $leCoursAction->getPrix();
        }
        return $resultat;
    }

    public function GetPrixActuelOneLine(): float
    {   
        return $this->lescoursactions[count($this->lescoursactions)- 1]->getPrix();
        // return $this->lescoursaction[X - 1]->getPrix();
        // return end($this->lescoursaction)->getPrix(); ### Equivalent
    }
    //3 Calculer Volume total des transactions effectuées sur cette action en utilisant les données de l'entité Transaction. 
    // Cela pourrait être le total des quantités achetées  sur une période donnée.

    /* ********************************************************************
    public function GetTotalTransaction(\DateTimeInterface $date, \DateTimeInterface $fin): int
    {
        $volumeTotal = 0;
        foreach ($this->lestransactions as $transation) 
        {
        if($transaction -> getOperation() === 'achat' &&
            $transaction ->getDateTransaction() >= $debut &&
            $transaction ->getDateTransaction() <= $fin)
            {
            $volumeTotal += $transaction->getQuantite();
            }
        }
    }
    *********************************************************************** */ 
    // Pour Test = donné par Thierry
    public function calculerVolumeAchatsPourTest(\DateTimeInterface $debut, \DateTimeInterface $fin): int
    {
        $volumeTotal = 0;

        foreach ($this->getLestransaction() as $transaction) {
            // Vérifier si la transaction est un achat et si elle est dans la période donnée
            if ($transaction->getOperation() === 'achat' &&
                $transaction->getDatetransaction() >= $debut &&
                $transaction->getDatetransaction() <= $fin)
                {
                $volumeTotal += $transaction->getQuantite();
            }
        }
        return $volumeTotal;
    }
    public function getPrixMoyen(string $operation): float
    {
        $resultat = 0.0; // Initialisation VARIABLE de notre moyenne (ce qu'on recherche)
        $montant = 0; // MONTANT total des transactions d'une action
        $sommeDesQuantites = 0;

        foreach($this->lestransaction as $laTransaction)
        {
            if($laTransaction->getOperation() === $operation )
            {
                $montant += $laTransaction->getCoursTransaction() * $laTransaction->getQuantite() ;
                $sommeDesQuantites += $laTransaction->getQuantite();
            }

            if($montant > 0) {
            $resultat = $montant / $sommeDesQuantites;
            }
        }
        return $resultat;
    }
    //4 Compter le nombre total de transactions effectuées avec cette action. 
    //Cette information peut être utile pour évaluer la liquidité ou la popularité de l'action.

    public function getQuantiteTotal(): int
    {
        
        $transactions = $this->getLestransaction();
        $resultat = count ($transactions);
       

        if($transactions === 0) {
            return 0;
        }

        $resultat = 0; 
        foreach ($transactions as $transaction) {
            $resultat += $transaction ->getQuantite();
        }

        return $resultat;
    }
    // Une méthode pour calculer le bilan total (profit ou perte) pour un trader spécifique en relation avec cette action, en utilisant les données des transactions.

    public function getBilan(Trader $trader): float // POUR UN TRADER EN PARTICULIER
    {
        $bilan = 0.00;

        foreach($this->lestransaction as $uneTransaction) // Une transaction De la collection de transaction 
        {
            if($uneTransaction->getLetrader() === $trader) // Pour un trader en particulier
            {   
                // On souhaite savoir si le trader et gagnant. le cours de l'action lors de la transaction
                if($uneTransaction ->getOperation() === "achat") // Une operation 
                {
                    $bilan -= $uneTransaction->getQuantite()* $uneTransaction->getCoursTransactionAuPlusProche();
                    

                }
                else
                {
                    $bilan += $uneTransaction->getQuantite()* $uneTransaction->getCoursTransactionAuPlusProche();
                }
            }
        }
        return $bilan;
    }
    //4 CORRECTION
    public function getVolumeTransaction(string $param = null) :int
    {
        $volume = 0;
        
        //$volume = count($this->$lestransactions);

        foreach($this->lestransaction as $uneTransaction)
        {
           if($uneTransaction->getOperation() === $param || !$param )
           {
            $volume += $uneTransaction->getQuantite();
           }        
        }


        return $volume;
    }
   
    // BILAN GENERAL POUR CONTROLLER
    public function getBilanGeneral(Trader $trader) :float
    { 
        $bilan = 0.00;

        foreach($this->lestransaction as $uneTransaction)
        {
            if($uneTransaction->getLetrader() === $trader)
            {
                if($uneTransaction->getOperation() === "achat")
                {

                    $bilan -= $uneTransaction->getQuantite()*$uneTransaction->getCoursTransactionAuPlusProche();
                }
                else
                {
                    $bilan += $uneTransaction->getQuantite()*$uneTransaction->getCoursTransactionAuPlusProche();

                }
            }
        }
        return $bilan;
    }
    public function getCoursActionValide(\DateTimeInterface $maDate): ?float
    {
        $coursAuPlusProche = 0.00;

        if(count($this->lescoursactions)==0)
        {
            return  $this->getPrix();
        }

        foreach($this->lescoursactions as $unCoursAction)
        {
            if($unCoursAction->getDatecoursaction()->format("Y-m-d") === $maDate->format("Y-m-d"))
        {
            return $unCoursAction->getPrix();
        }
        elseif($unCoursAction->getDatecoursaction()->format("Y-m-d")>$maDate->format("Y-m-d"))
        {
            return $coursAuPlusProche;
        }
        else
        {
            $coursAuPlusProche = $unCoursAction->getPrix();
        }
        
        }
        return $coursAuPlusProche;
    }

    
}