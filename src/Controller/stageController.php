<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use App\Service\UserService\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\StatistiqueService;



#[Route('/stage')]
class stageController extends AbstractController
{
    private $service;
    private $statistiqueService;

    public function __construct(UserService $service, StatistiqueService $statistiqueService)
    {
        $this->service = $service;
        $this->statistiqueService = $statistiqueService;
    }
    #[Route('/', name: 'app_stage_stage', methods: ['GET'])]
    public function stage(OffreRepository $offreRepository): Response
    {
        $statistiques = $this->statistiqueService->getStatistiquesCandidaturesParOffre();
        return $this->render('stage/stage.html.twig', [
            'offres' => $offreRepository->findAll(),
            'statistiques' => $statistiques,
        ]);
    }
    #[Route('/offre/{id}', name: 'app_stage_show', methods: ['GET'])]
    public function show(Offre $offre,CandidatureRepository $repo , $id , OffreRepository $repo1): Response
    {
        $offre=$repo1->find($id);
        $list=$repo->findByOffre($offre);
        return $this->render('stage/show.html.twig', [
            'offre' => $offre,
            'list'=> $list,
        ]);
    }
}
