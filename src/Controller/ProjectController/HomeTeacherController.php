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
use Symfony\Component\Security\Core\Security;
use App\Entity\User;

class HomeTeacherController extends AbstractController
{
    private $userId;
    public function __construct(Security $security)
    {
        $this->userId = $security->getUser()->getId();
        if (!$this->userId) {
            throw new \RuntimeException('User not authenticated');
        }
    }
    #[Route("/home-teacher" ,name: "home_teacher")]
    public function index(Request $request, ProjectServiceService $projectService, ProjectRepository $projectRepository): Response
    {
        $selectedClass = $request->query->get('classe');
        $projects = [];
        $classes = $projectRepository->findAllClasses(); // Récupérer toutes les classes
        if ($selectedClass) {
            $projects = $projectRepository->findBy(['classe' => $selectedClass , 'teacher'=>$this->userId]);
        } else {
            $projects = $projectService->getAllProjectsForTeacher($this->userId); // Récupérer les projets de l'enseignant
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
