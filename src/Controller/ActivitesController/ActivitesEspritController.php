<?php

namespace App\Controller\ActivitesController;

use App\Entity\Activite; // Assurez-vous que c'est le bon chemin vers votre entité Activite
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ActivitesEspritController extends AbstractController
{
    #[Route('/activites/esprit', name: 'app_activites_esprit', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Récupération des informations sur les activités depuis la base de données
        $activites = $this->getDoctrine()->getRepository(Activite::class)->findAll();
        // Définir selectedPrice à une valeur par défaut pour éviter les erreurs dans le template
        $selectedPrice = null;

        // Passage des activités et du selectedPrice à la vue Twig
        return $this->render('activites_esprit/index.html.twig', [
            'activites' => $activites,
            'selectedPrice' => $selectedPrice, // Passer la variable selectedPrice
        ]);
    }
}

