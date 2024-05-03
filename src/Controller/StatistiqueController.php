<?php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\StatistiqueService;

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/statistiques", name="statistiques")
     */
    public function statistiques(StatistiqueService $statistiqueService)
    {
        $statistiques = $statistiqueService->getStatistiquesCandidaturesParOffre();

        return $this->render('statistiques.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }
}
