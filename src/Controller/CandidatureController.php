<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use App\Service\UserService\UserService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Offre;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Service\StatistiqueService;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    private $service;
    private $flash;
    private $statistiqueService;

    public function __construct(UserService $service, StatistiqueService $statistiqueService)
    {
        $this->service = $service;
        $this->flash = 'Hello'; // Example value
        $this->statistiqueService = $statistiqueService;
    }
   
    #[Route('/', name: 'app_candidature_index', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_candidature_new', methods: ['GET', 'POST'])]
public function new(Request $request, UserRepository $repo, EntityManagerInterface $entityManager, Security $security): Response
{
    $user = $repo->getByEmail($security->getUser()->getUserIdentifier());
    $candidature = new Candidature();
    $candidature->setStatus('En attente');
    // Récupérer l'offre_id depuis la requête
    $offreId = $request->query->get('offre_id');
    // Utilisez $offreId pour charger l'offre associée depuis la base de données
    $offre = $entityManager->getRepository(Offre::class)->find($offreId);

    $form = $this->createForm(CandidatureType::class, $candidature);
    $form->handleRequest($request);
    $cvFile = $form->get('cv')->getData();

    if ($form->isSubmitted() && $form->isValid()) {
        $candidature->setDate(new \DateTime());
        $candidature->setUser($user);
        $candidature->setOffre($offre);

        if ($cvFile) {
            $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
            // Ceci est nécessaire pour éviter les collisions de noms de fichiers
            $newFilename = $originalFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
            $cvDirectory = $this->getParameter('candidature_directory');
            echo "Chemin du répertoire des CV : " . $cvDirectory;            // Déplacez le fichier dans le répertoire où les CV sont stockés
            $cvFile->move(
                $this->getParameter('candidature_directory'),
                $newFilename
            );

            // Mettez à jour le champ cv de l'entité Candidature avec le nom du fichier
            $candidature->setCv($newFilename);
        }

        $entityManager->persist($candidature);
        $entityManager->flush();

        return $this->redirectToRoute('candidature_show', ['id' => $candidature->getId()]);
    }

    return $this->render('candidature/new.html.twig', [
        'candidature' => $candidature,
        'form' => $form->createView(),
        'offre_id' => $offreId,
    ]);
}


    #[Route('/{id}', name: 'app_candidature_show', methods: ['GET', 'POST'])]
    public function show(Candidature $candidature): Response
    {
        $url = $this->generateUrl('candidature_show', ['id' => $candidature->getId()]);
        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cvFile = $form->get('cv')->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                $cvFile->move(
                    $this->getParameter('candidature_directory'),
                    $newFilename
                );

                $candidature->setCv($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }  

    #[Route('/delete/{id}', name: 'app_candidature_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/accepter/{id}', name: 'accepter')]
    public function accepterCandidature(Candidature $candidature, EntityManagerInterface $entityManager, Request $request): Response
    {
        $referer = $request->headers->get('referer');
        $candidature->setStatus('Acceptée');
        $entityManager->flush();
        $this->addFlash('success', 'Candidature acceptée avec succès.');
        return $this->redirect($referer);

    }

    #[Route('/refuser/{id}', name: 'refuser')]
public function refuserCandidature(Candidature $candidature, EntityManagerInterface $entityManager, Request $request): Response
{
    $referer = $request->headers->get('referer');
    $candidature->setStatus('Refusée');
    $entityManager->flush();

    $this->addFlash('danger', 'Candidature refusée avec succès.');

    return $this->redirect($referer);
}



#[Route('/download-cv/{id}', name: 'download_cv')]
public function downloadCv($id, CandidatureRepository $repo): Response
{
    $candidature = $repo->find($id);
    if (!$candidature) {
        throw $this->createNotFoundException('Candidature not found');
    }

    // Path to the CV file
    $cvPath = $this->getParameter('candidature_directory') . '/' . $candidature->getCv();

    // Create the response
    $response = new BinaryFileResponse($cvPath);

    // Set the headers to indicate that this is a CV file download
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        $candidature->getCv()
    ));

    return $response;
}


#[Route('/statistiques', name: 'app_candidature_statistiques', methods: ['GET'])]
public function statistiques(): Response
{
    $statistiques = $this->statistiqueService->getStatistiquesCandidaturesParOffre();

    return $this->render('candidature/statistiques.html.twig', [
        'statistiques' => $statistiques,
    ]);
}

    

}
