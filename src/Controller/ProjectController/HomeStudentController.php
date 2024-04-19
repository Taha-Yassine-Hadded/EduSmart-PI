<?php

namespace App\Controller\ProjectController;

use App\Service\Projet\ProjectService;
use App\Service\Projet\TacheService;
use App\Service\Projet\FichierService;
use App\Entity\EtatEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tache; // Ajout du namespace pour la classe Tache
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Projet\ProjectMembersService;
use App\Entity\ProjectMembers;
use App\Entity\Fichier;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class HomeStudentController extends AbstractController
{
    private $projectService;
    private $tacheService;
    private $entityManager;
    private $fichierService;
    private $projectMembersService;


    public function __construct(EntityManagerInterface $entityManager,ProjectService $projectService, TacheService $tacheService , ProjectMembersService $projectMembersService , FichierService $fichierService)
    {
        $this->projectService = $projectService;
        $this->fichierService = $fichierService;
        $this->tacheService = $tacheService;
        $this->entityManager = $entityManager;
        $this->projectMembersService = $projectMembersService;


    }

    #[Route('/home-student', name: 'home-student')]
    public function showProjects(): Response
    {
        $userId = 2; // Remplacez ceci par l'ID de l'utilisateur dont vous voulez récupérer les projets

        // Récupérer les noms de projet pour affichage
        $projectNames = $this->projectService->getProjectNamesForUserId($userId);


        return $this->render('/Project/student/HomeStudent.html.twig', [
            'projectNames' => $projectNames,
            'taches' => [], // Initialiser avec une liste vide, car aucune tâche n'est sélectionnée par défaut

        ]);
    }

    #[Route('/home-student/{projectId}', name: 'home-student-project')]
    public function index(Request $request, $projectId): Response
    {
        $userId = 2;

        $taches = $this->tacheService->getTachesByUserIdAndProjectId($userId, $projectId);
        $projectNames = $this->projectService->getProjectNamesForUserId($userId);


        // Utiliser la méthode getValidStates() de l'énumération EtatEnum pour obtenir les valeurs valides
        $etatEnumValues = EtatEnum::getValidStates();

        return $this->render('/Project/student/HomeStudent.html.twig', [
            'projectNames' => $projectNames,
            'taches' => $taches,
            'etatEnumValues' => $etatEnumValues,
        ]);
    }


    #[Route('/update-tache-etat/{tacheId}/{newState}', name: 'update_tache_etat')]
    public function updateEtat($tacheId, $newState, TacheService $tacheService): JsonResponse
    {
        try {
            $tache = $tacheService->updateEtat($tacheId, $newState);
            if (!$tache) {
                return new JsonResponse(['success' => false, 'message' => 'Tâche non trouvée'], Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/update-task-description/{taskId}', name: 'update_task_description')]
    public function updateTaskDescription(Request $request, $taskId, TacheService $tacheService): JsonResponse
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $newDescription = $requestData['description'] ?? null;
            if ($newDescription === null) {
                return new JsonResponse(['success' => false, 'message' => 'Description manquante'], Response::HTTP_BAD_REQUEST);
            }
            $tacheService->updateTaskDescription($taskId, $newDescription);
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/update-task-deadline/{tacheId}', name: 'update-task-deadline')]
    public function updateTaskDeadline($tacheId, Request $request, TacheService $tacheService): JsonResponse
    {
        try {
            // Récupérer la nouvelle deadline à partir des données de la requête
            $requestData = json_decode($request->getContent(), true);
            $newDeadline = $requestData['deadline'] ?? null;

            // Vérifier si la nouvelle deadline est valide
            if ($newDeadline === null) {
                return new JsonResponse(['success' => false, 'message' => 'Nouvelle date de délai manquante'], Response::HTTP_BAD_REQUEST);
            }

            // Appeler la méthode du service pour mettre à jour la deadline de la tâche
            $tache = $tacheService->updateDeadline($tacheId, $newDeadline);

            // Vérifier si la tâche a été trouvée et mise à jour avec succès
            if (!$tache) {
                return new JsonResponse(['success' => false, 'message' => 'Tâche non trouvée'], Response::HTTP_NOT_FOUND);
            }

            // Retourner une réponse JSON indiquant le succès de la mise à jour
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            // Capturer toute exception survenue et renvoyer une réponse d'erreur
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la mise à jour de la date de délai de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/delete-tasks', name: 'delete_tasks')]
    public function deleteTasks(Request $request, TacheService $tacheService): JsonResponse
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $tasksToDelete = $requestData['tasks'] ?? [];

            foreach ($tasksToDelete as $taskId) {
                $tacheService->deleteTaskById($taskId);
            }

            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/add-task/{projectId}', name: 'add_task')]
    public function addTask(Request $request, ProjectMembersService $projectMembersService, TacheService $tacheService, int $projectId): JsonResponse
    {
        try {
            // Récupérer l'ID de l'utilisateur actuel
            $userId = 2;
            dump($projectId);
            // Récupérer l'ID du membre pour l'utilisateur actuel et le projet spécifié
            $memberId = $projectMembersService->findIdMemberByUserIdAndProjectId($userId, $projectId);

            // Récupérer les données du formulaire
            $requestData = json_decode($request->getContent(), true);
            $description = $requestData['description'] ?? null;
            $deadline = $requestData['deadline'] ?? null;

            // Vérifier que la description et la deadline ne sont pas vides
            if (!$description || !$deadline) {
                return new JsonResponse(['success' => false, 'message' => 'Description ou deadline manquante'], Response::HTTP_BAD_REQUEST);
            }

            // Créer une nouvelle instance de tâche
            $task = new Tache();
            $task->setDescription($description);
            $task->setDateAjout(new \DateTime()); // Date actuelle
            $task->setDedline(new \DateTime($deadline));
            $task->setEtat(EtatEnum::AFaire); // Utilisation de la valeur française pour l'état

            // Récupérer l'objet ProjectMembers correspondant à l'ID du membre
            $member = $projectMembersService->findById($memberId);

            // Vérifier si le membre existe
            if (!$member) {
                return new JsonResponse(['success' => false, 'message' => 'Membre introuvable'], Response::HTTP_NOT_FOUND);
            }

            // Assigner le membre à la tâche
            $task->setMember($member);

            // Ajouter la tâche à la base de données en utilisant le service TacheService
            $tacheService->ajouterTache($task);

            // Retourner une réponse JSON avec l'ID de la tâche ajoutée
            return new JsonResponse(['success' => true, 'taskId' => $task->getId()]);
        } catch (\Exception $e) {
            // Gérer les erreurs
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/import-fichier', name: 'import_fichier')]
    public function importFichier(Request $request, ProjectMembersService $projectMembersService): Response
    {
        // Récupérer l'ID de l'utilisateur (vous pouvez remplacer 2 par la méthode appropriée pour récupérer l'ID de l'utilisateur)
        $userId = 2;

        // Récupérer l'ID du projet à partir des données de la requête
        $projectId = $request->request->get('projectId');

        // Trouver l'ID du membre
        $memberId = $projectMembersService->findIdMemberByUserIdAndProjectId($userId, $projectId);

        // Vérifier si un membre a été trouvé pour cet utilisateur et ce projet
        if (!$memberId) {
            return new Response('Aucun membre trouvé pour cet utilisateur et ce projet', Response::HTTP_BAD_REQUEST);
        }

        // Récupérer le fichier envoyé depuis le formulaire
        $uploadedFile = $request->files->get('file');

        // Vérifier si un fichier a été envoyé
        if (!$uploadedFile instanceof UploadedFile) {
            return new Response('Aucun fichier n\'a été envoyé', Response::HTTP_BAD_REQUEST);
        }

        // Générer un nom unique pour le fichier pour éviter les collisions
        $fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

        // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
        try {
            $uploadedFile->move(
                $this->getParameter('dossier_fichiers'), // Le répertoire où stocker les fichiers (à définir dans votre configuration Symfony)
                $fileName
            );
        } catch (FileException $e) {
            // Gérer les erreurs lors du déplacement du fichier
            // Par exemple, renvoyer un message d'erreur ou rediriger avec un message d'erreur
            return new Response('Une erreur s\'est produite lors de l\'import du fichier', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Créer une nouvelle instance de l'entité Fichier
        $fichier = new Fichier();
        $fichier->setNom($uploadedFile->getClientOriginalName()); // Nom d'origine du fichier
        $fichier->setPath($fileName); // Chemin vers le fichier
        $fichier->setDateAjout(new \DateTime());

        // Récupérer l'objet ProjectMembers correspondant à l'ID du membre
        $member = $this->getDoctrine()->getRepository(ProjectMembers::class)->find($memberId);

        // Associer le membre au fichier
        $fichier->setMember($member);

        // Enregistrez l'entité Fichier en base de données en utilisant Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fichier);
        $entityManager->flush();

        // Rediriger l'utilisateur vers une autre page ou retourner un message de confirmation
        return new Response('Le fichier a été importé avec succès', Response::HTTP_OK);
    }

}