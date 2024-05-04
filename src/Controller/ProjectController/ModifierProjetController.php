<?php

namespace App\Controller\ProjectController;

use App\Entity\ProjectMembers;
use App\Repository\UserRepository;
use App\Service\Projet\ProjectMembersService;
use App\Service\Projet\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ModifierProjetController extends AbstractController

{
    private $projectMembersService;
    private $entityManager;

    public function __construct(ProjectMembersService $projectMembersService, EntityManagerInterface $entityManager)
    {
        $this->projectMembersService = $projectMembersService;
        $this->entityManager = $entityManager;
    }
    #[Route("/modifier-projet/{id}", name: "modifier-projet")]
    public function index($id, ProjectService $projectService): Response
    {
        $project = $projectService->getById($id);
        if (!$project) {
            throw $this->createNotFoundException('Projet non trouvé pour l\'ID : '.$id);
        }

        return $this->render('/Project/ModifierProjet.html.twig', [
            'id' => $id,
            'project' => $project,
        ]);
    }

    #[Route("/update-project-data", name: "update_project_data", methods: ["POST"])]
    public function updateProjectData(Request $request, ProjectService $projectService): JsonResponse
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            $projectId = $requestData['id'];
            $type = $requestData['type'];
            $newValue = $requestData['newValue'];

            $project = $projectService->getById($projectId);
            if (!$project) {
                return new JsonResponse(['error' => 'Projet non trouvé'], 404);
            }

            switch ($type) {
                case 'nom':
                    $project->setNom($newValue);
                    break;
                case 'description':
                    $project->setDescription($newValue);
                    break;
                case 'classe':
                    $project->setClasse($newValue);
                    break;
                case 'matiere':
                    $project->setMatiere($newValue);
                    break;
                default:
                    return new JsonResponse(['error' => 'Type de données invalide'], 400);
            }

            $projectService->update($project);

            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la mise à jour du projet: ' . $e->getMessage()], 500);
        }
    }

    #[Route("/get-project-members/{id}", name: "get_project_members")]
    public function getProjectMembers($id, ProjectMembersService $projectMembersService): JsonResponse
    {
        try {
            $members = $projectMembersService->findMembersByProjectId($id);
            $membersArray = [];
            foreach ($members as $member) {
                $memberEmails = [];
                foreach ($member->getStudent() as $student) {
                    $memberEmails[] = $student->getEmail();
                }
                $membersArray[] = [
                    'id' => $member->getId(),
                    'emails' => $memberEmails,
                ];
            }
            return new JsonResponse($membersArray);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la récupération des membres du projet: ' . $e->getMessage()], 500);
        }
    }



// Votre code existant...



    #[Route("/add-project-member/{id}", name: "add_project_member", methods: ["POST"])]
    public function addProjectMember($id, Request $request, UserRepository $userRepository, ProjectService $projectService, ProjectMembersService $projectMembersService): JsonResponse
    {
        try {
            // Récupérer l'adresse e-mail envoyée par l'utilisateur depuis le corps de la requête
            $requestData = json_decode($request->getContent(), true);
            $email = $requestData['email'];

            // Recherchez l'utilisateur par son adresse e-mail
            $user = $userRepository->findOneBy(['email' => $email]);

            // Vérifiez si l'utilisateur existe
            if ($user === null) {
                return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
            }

            // Obtenez le projet correspondant à l'ID
            $project = $projectService->getById($id);

            // Vérifiez si le projet existe
            if (!$project) {
                return new JsonResponse(['error' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
            }

            // Créez une nouvelle instance de ProjectMembers
            $projectMember = new ProjectMembers();
            $projectMember->addStudent($user);
            $projectMember->setProject($project);
            $projectMember->setJoinedAt(new \DateTime());

            // Enregistrez le nouveau membre
            $projectMembersService->createProjectMember($projectMember);

            // Préparez la réponse JSON
            $response = [
                'success' => true,
                'message' => 'Membre ajouté avec succès.',
                'email' => $email,
            ];

            return new JsonResponse($response);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'ajout du membre: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route("/delete-project-member/{id}", name: "delete_project_member", methods: ["DELETE"])]
    public function deleteProjectMember($id, ProjectMembersService $projectMembersService): JsonResponse
    {
        try {
            $projectMember = $projectMembersService->findById($id);
            if (!$projectMember) {
                return new JsonResponse(['error' => 'Membre non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $projectMembersService->deleteProjectMember($projectMember);

            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression du membre: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
