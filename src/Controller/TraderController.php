<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TraderRepository;
use App\Entity\Trader;

class TraderController extends AbstractController
{

#[Route('/trader', name: 'app_trader')]
public function index(): Response
{
    return $this->render('trader/index.html.twig', [
    'controller_name' => 'TraderController',
]);
}
// Va reconnaitre qu'il y a un ID Va chercher l'objet qui correspond à cette ID

#[Route('/trader/{id}', name: 'trader_show')]
public function showTrader(Trader $trader): Response 
{
// Pas besoin de vérifier si le trader existe, Symfony s'en occupe
    return $this->render('trader/show.html.twig', [
    'trader' => $trader,
]);
}
#[Route('/traders', name: 'traders_list')]

public function listTraders(TraderRepository $traderRepository): Response
{
    $traders = $traderRepository->findAll(); // $ objet de la classe traderrepository -> renvoie moi tout les traders

    return $this->render('trader/list.html.twig', [ // ON envoie dans une liste du twig le resultat 
        'traders' => $traders,
    ]);
}

#[Route('/trader/historique/{id}', name: 'app_trader_historique')]

public function traderhistorique(Trader $trader): Response  // ON VA DEMANDER TT LES TRANSACTIONS D UN TRADER 
{
    return $this->render('trader/historique.html.twig', [
        'trader' => $trader, // RETOURN TT LES DETAILS DE LA CLASSE TRADER
    ]);
}

#[Route('/trader/diversification/{id}', name: 'app_trader_diversification')]
public function diversificationPortfolio(Trader $trader): Response  // ON VA DEMANDER TT LES TRANSACTIONS D UN TRADER 
{
    return $this->render('trader/diversification.html.twig', [
        'trader' => $trader, // RETOURN TT LES DETAILS DE LA CLASSE TRADER
    ]);
}
}
