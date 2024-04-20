<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
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

#[Route('/offre')]
class OffreController extends AbstractController
{
    private $service;
    public function __construct(UserService $service)
    {
        $this->service=$service;
    }
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/offre',name: 'app_offre_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
       
    
        if ($form->isSubmitted() && $form->isValid()) {
            $offre->setDate(new \DateTime());
            $offre->setEntreprise($this->service->getUserById(10));
            $entityManager->persist($offre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('offre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/offre/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre,CandidatureRepository $repo , $id , OffreRepository $repo1): Response
    {
        $offre=$repo1->find($id);
        $list=$repo->findByOffre($offre);
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'list'=> $list,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(OffreType::class, $offre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $message = Offre::creerMessage('Titre de l\'offre', 'Description de l\'offre', 'Compétences requises', 'nouveau nbr');
        echo $message;        
    
        $entityManager->flush();

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('offre/edit.html.twig', [
        'offre' => $offre,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/offres/{id}/candidatures", name="app_offre_candidatures")
     */
    public function candidatures(Offre $offre): Response
    {
        // Récupérez les candidatures associées à cette offre
        $candidatures = $offre->getCandidatures();

        // Affichez les candidatures dans un template
        return $this->render('offre/candidatures.html.twig', [
            'offre' => $offre,
            'candidatures' => $candidatures,
        ]);
    }
    #[Route('/list', name: 'app_offre_list', methods:['GET', 'POST'])]
    public function list(OffreRepository $offreRepository , Request $request): Response
    {

        return $this->render('offre/list.html.twig', [
            
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'search_offres')]
public function search(Request $request, OffreRepository $offreRepository): Response
{
    $searchTerm = $request->request->get('search');
    $offres = $offreRepository->searchByTitleOrDescription($searchTerm);

    return $this->render('offre/list.html.twig', [
        'offres' => $offres,  // Utilisez le même nom de variable ici
    ]);
}


}

