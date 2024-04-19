<?php
namespace App\Controller\ProjectController;

use App\Controller\ProjectController\JsonResponse;
use App\Repository\ProjectRepository;
use App\Service\Projet\ProjectService as ProjectServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeTeacherController extends AbstractController
{
    #[Route("/home-teacher" ,name: "home_teacher")]
    public function index(Request $request, ProjectServiceService $projectService, ProjectRepository $projectRepository): Response
    {
        $selectedClass = $request->query->get('classe');
        $projects = [];
        $classes = $projectRepository->findAllClasses(); // Récupérer toutes les classes
        if ($selectedClass) {
            $projects = $projectRepository->findBy(['classe' => $selectedClass]);
        } else {
            $projects = $projectService->getAll(); // Récupérer tous les projets si aucune classe n'est sélectionnée
        }
        return $this->render('/Project/HomeTeacher.html.twig', [
            'projects' => $projects, // Passer les projets à la vue
            'classes' => $classes, // Passer les classes à la vue
        ]);
    }
    #[Route("/delete-project/{id}", name: "delete_project", methods: ["POST"])]
    public function deleteProject(int $id, ProjectServiceService $projectService): Response
    {
        $projectService->delete($id);
        return new JsonResponse(['message' => 'Project deleted successfully']);
    }

    #[Route("/ajouter_projet")]
    public function addProjectPage(): RedirectResponse
    {
        return $this->redirectToRoute('ajouter_projet'); // Assurez-vous d'avoir une route nommée "add_project" pour la page d'ajout de projet
    }
}
