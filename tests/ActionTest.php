<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Transaction;
use App\Entity\Action;
use App\Entity\Trader;


class ActionTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testCalculerVolumeAchats()
    {
        $action = new Action();
        $debut = new \DateTime('2021-01-01');
        $fin = new \DateTime('2021-01-31');

        // Ajouter des transactions fictives
        $transaction1 = new Transaction();
        $transaction1->setDatetransaction(new \DateTime('2021-01-10'));
        $transaction1->setQuantite(10);
        $transaction1->setOperation('achat');
        $action->addLestransaction($transaction1);

        $transaction2 = new Transaction();
        $transaction2->setDatetransaction(new \DateTime('2021-01-20'));
        $transaction2->setQuantite(20);
        $transaction2->setOperation('achat');
        $action->addLestransaction($transaction2);

        // Ajouter une transaction qui ne devrait pas être comptée
        $transaction3 = new Transaction();
        $transaction3->setDatetransaction(new \DateTime('2021-02-01'));
        $transaction3->setQuantite(30);
        $transaction3->setOperation('achat');
        $action->addLestransaction($transaction3);

        $volumeTotal = $action->calculerVolumeAchatsPourTest($debut, $fin);
        $this->assertEquals(30, $volumeTotal, "Le volume total des achats devrait être de 30");
    }
    public function testGetPrixMoyen()
    {
        $action = new Action();
        
        // Créer des mocks pour Transaction
        $transaction1 = $this->createMock(Transaction::class);
        $transaction1->method('getOperation')->willReturn('achat');
        $transaction1->method('getCoursTransaction')->willReturn(100.0);
        $transaction1->method('getQuantite')->willReturn(5);
        
        $transaction2 = $this->createMock(Transaction::class);
        $transaction2->method('getOperation')->willReturn('achat'); // SI ya qq'un qui appel getoperation = achat
        $transaction2->method('getCoursTransaction')->willReturn(200.0);
        $transaction2->method('getQuantite')->willReturn(3);

        // Ajouter les transactions fictives à l'action
        $action->addlesTransaction($transaction1);
        $action->addlesTransaction($transaction2);

        // Calculer le prix moyen des achats
        $prixMoyen = $action->getPrixMoyen('achat');

        // Le prix moyen attendu est (100*5 + 200*3) / (5 + 3) = 137.5
        $this->assertEquals(137.5, $prixMoyen, "Le prix moyen des achats devrait être de 137.5");
    }
    public function testCalculerBilanPourTrader()
    {
        $action = new Action();
        $trader = $this->createMock(Trader::class);

        // Créer des mocks pour Transaction
        $transactionAchat = $this->createMock(Transaction::class);
        $transactionAchat->method('getLetrader')->willReturn($trader);
        $transactionAchat->method('getOperation')->willReturn('achat');
        $transactionAchat->method('getCoursTransaction')->willReturn(100.0);
        $transactionAchat->method('getQuantite')->willReturn(10);

        $transactionVente = $this->createMock(Transaction::class);
        $transactionVente->method('getLetrader')->willReturn($trader);
        $transactionVente->method('getOperation')->willReturn('vente');
        $transactionVente->method('getCoursTransaction')->willReturn(150.0);
        $transactionVente->method('getQuantite')->willReturn(5);

        // Ajouter les mocks à l'action
        $action->addlesTransaction($transactionAchat);
        $action->addlesTransaction($transactionVente);

        // Calculer le bilan
        $bilan = $action->calculerBilanPourTrader($trader);

        // Le bilan attendu est (5 * 150) - (10 * 100) = 750 - 1000 = -250
        $this->assertEquals(-250, $bilan, "Le bilan total devrait être de -250");
    }

}
