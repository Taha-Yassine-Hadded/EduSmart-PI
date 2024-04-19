<?php


namespace App\Controller\ProjectController;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\Projet\ProjectService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ...

class AjouterProjetController extends AbstractController
{
    #[Route("/ajouter_projet" , name: "ajouter_projet")]
    public function ajouterProjet(Request $request, ProjectService $projectService): Response
    {
        $project = new Project();
        // Récupérer l'utilisateur actuellement connecté (enseignant)
        $teacher = $this->getUser();

        // Définir l'enseignant du projet
        $project->setTeacher($teacher);

        // Autres attributs du projet
        $project->setCreatedAt(new DateTime());
        $project->setUpdatedAt(new DateTime());

        // Création du formulaire et traitement de la requête
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $projectService->add($project);
                $this->addFlash('success', 'Le projet a été ajouté avec succès.');
                // Redirection vers la page AjouterMembre.html.twig
                return new RedirectResponse($this->generateUrl('ajouter_membre'));
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'ajout du projet: ' . $e->getMessage());
            }
        }

        return $this->render('/Project/AjouterProjet.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
