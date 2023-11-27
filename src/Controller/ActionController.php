<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActionRepository;
use App\Repository\TraderRepository;
use App\Entity\Action;
use App\Entity\Trader;

class ActionController extends AbstractController
{
    #[Route('/action', name: 'app_action')]
    public function index(): Response
    {
        return $this->render('action/index.html.twig', [
            'controller_name' => 'ActionController',
        ]);
    }
    // RETOURNER UNE ACTION

    #[Route('/action/{id}', name: 'action_show')]
public function showAction(Action $action): Response 
{
    // Pas besoin de vérifier si le trader existe, Symfony s'en occupe
    return $this->render('action/show.html.twig', [
        'action' => $action,
    ]);
}

    // RETOURNER UNE LISTE D ACTION
    #[Route('/actions', name: 'actions_list')]
public function listAction(ActionRepository $actionRepository): Response
{
    $actions = $actionRepository->findAll(); // $ objet de la classe 
    return $this->render('action/list.html.twig', [ // ON envoie dans une liste du twig le resultat 
        'actions' => $actions,
    ]);
}
    // CALCUL DU COURS MOYEN
    #[Route('/actions/{id}/cours-moyen', name: 'actions_cours_moyen')] // ON MET UN - PLUTOT QUE _ pour tromper l'ennemie
    public function coursMoyen(Action $action): Response
    {
        $moyenne = $action->calculerCoursMoyen(); // $ objet de la classe 
    
        return $this->render('action/cours_moyen.html.twig', [ // ON envoie dans une liste du twig le resultat 
            'action' => $action,
            'moyenne' => $moyenne,
        ]);
    }

    /*#[Route('/action/{id}/getVolume', name:'app_volume')] 
    public function getVolume(Action $action): Response
    {
        
        $volume = $action->getVolumeTransaction();

        return $this->render('action/volume.html.twig', ['leVolume' => $volume]);
    } */

    #[Route('/action/getVolume', name:'app_volume')] 
    public function getVolume(ActionRepository $actionRepository): Response
    {
        $action = $actionRepository->find(1);
        $volume = $action->getVolumeTransaction();

        return $this->render('action/volume.html.twig', ['leVolume' => $volume]);
    }
    #[Route('/action/getBilanGeneral/{r2}/{d2}', name:'app_bilan_general')] 
    public function getBilanGeneral(Trader $r2, Action $d2) : Response
    {
       
        return $this->render('action/bilangeneral.html.twig', 
        ['bilangeneral' => $d2 ->getBilanGeneral($r2)]);
    }
}
