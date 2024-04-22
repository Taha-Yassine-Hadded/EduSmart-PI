<?php

namespace App\Controller\ActivitesController;

use App\Entity\Activite; // Assurez-vous que c'est le bon chemin vers votre entité Activite
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivitesEspritController extends AbstractController
{
    #[Route('/activites/esprit', name: 'app_activites_esprit')]
    public function index(): Response
    {
        // Récupération des informations sur les activités depuis la base de données
        $activites = $this->getDoctrine()->getRepository(Activite::class)->findAll();

        // Passage des activités à la vue Twig
        return $this->render('activites_esprit/index.html.twig', [
            'activites' => $activites,
        ]);
    }
}
