<?php
namespace App\Controller\ActivitesController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activite;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscrire')]
class InscrireController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/new/{id_activite}', name: 'app_inscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $id_activite): Response
{
    $inscription = new Inscription();

    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    // Pré-remplir les champs du formulaire avec les données de l'utilisateur connecté s'il existe
    if ($user !== null) {
        $inscription->setNom($user->getNom());
        $inscription->setPrenom($user->getPrenom());
        $inscription->setEmail($user->getEmail());
    }

    // Ajouter l'activité à l'inscription
    $activite = $this->getDoctrine()->getRepository(Activite::class)->find($id_activite);
    $inscription->setActivite($activite);

    $form = $this->createForm(InscriptionType::class, $inscription);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->persist($inscription);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('inscrire/index.html.twig', [
        'inscription' => $inscription,
        'form' => $form,
    ]);
}


    #[Route('/', name: 'app_inscription_index', methods: ['GET'])]
    public function index(): Response
    {
        // Récupérer l'ID de la première activité
        $activites = $this->getDoctrine()->getRepository(Activite::class)->findAll();
        $idActivite = $activites[0]->getId(); // Supposons que la première activité ait l'ID le plus bas
    
        // Rediriger vers app_inscription_new avec l'ID de l'activité
        return $this->redirectToRoute('app_inscription_new', ['id_activite' => $idActivite]);
    }
}

