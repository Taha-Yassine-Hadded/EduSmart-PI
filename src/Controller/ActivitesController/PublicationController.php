<?php

namespace App\Controller\ActivitesController;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository, Security $security): Response
    {
        $user = $security->getUser();
        $username = $user ? $user->getUsername() : null;
    
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findAll(),
            'username' => $username,
        ]);
    }


    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
{
    if (!$this->isGranted('ROLE_CLUB')) {
        throw new AccessDeniedException('Access denied');
    }
    // Créer une nouvelle instance de l'entité Publication
    $publication = new Publication();

    // Définir la date par défaut comme la date du jour
    $publication->setDate(new \DateTime());

    // Récupérer l'utilisateur connecté
    $user = $security->getUser();
    $username = $user ? $user->getUsername() : null;

    // Créer le formulaire en passant l'entité Publication
    $form = $this->createForm(PublicationType::class, $publication);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($publication);
        $entityManager->flush();

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('publication/new.html.twig', [
        'publication' => $publication,
        'form' => $form,
        'username' => $username, // Passer le nom d'utilisateur à la vue
    ]);
}





    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_CLUB')) {
            throw new AccessDeniedException('Access denied');
        }
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_CLUB')) {
            throw new AccessDeniedException('Access denied');
        }
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}
