<?php


namespace App\Controller\ProjectController;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\Projet\MatiereService;
use App\Service\Projet\ProjectService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

// Assurez-vous d'importer la classe User

class AjouterProjetController extends AbstractController
{
    private $userId;
    public function __construct(Security $security)
    {
        $this->userId = $security->getUser()->getId();
        if (!$this->userId) {
            throw new \RuntimeException('User not authenticated');
        }
    }

    #[Route("/ajouter_projet" , name: "ajouter_projet")]
    public function ajouterProjet(Request $request, ProjectService $projectService, MatiereService $matiereService, FlashBagInterface $flashBag): Response
    {
        $project = new Project();

        // Récupérer l'utilisateur actuellement connecté (enseignant)
        $teacher = $this->getDoctrine()->getRepository(User::class)->find($this->userId);

        // Vérifier si l'enseignant existe
        if (!$teacher) {
            throw $this->createNotFoundException('Enseignant non trouvé');
        }

        // Définir l'enseignant du projet
        $project->setTeacher($teacher);

        // Autres attributs du projet
        $project->setCreatedAt(new DateTime());
        $project->setUpdatedAt(new DateTime());

        // Création du formulaire et traitement de la requête
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        // Initialiser la variable pour vérifier l'existence de la matière
        $matiereExists = true;

        if ($form->isSubmitted() && $form->isValid()) {
            $matiere = $project->getMatiere();
            if (!$matiereService->matiereExists($matiere)) {
                $matiereExists = false;
                $flashBag->add('error', 'La matière spécifiée n\'existe pas.');
            } else {
                try {
                    $projectService->add($project);
                    $this->addFlash('success', 'Le projet a été ajouté avec succès.');
                    // Redirection vers la page AjouterMembre.html.twig
                    return new RedirectResponse($this->generateUrl('ajouter_membre'));
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'ajout du projet: ' . $e->getMessage());
                }
            }
        }

        return $this->render('/Project/AjouterProjet.html.twig', [
            'form' => $form->createView(),
            'matiereExists' => $matiereExists, // Passer l'information à Twig
        ]);
    }
}
