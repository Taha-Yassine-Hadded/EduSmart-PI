<?php
namespace App\Controller\ProjectController;

use App\Entity\Project;
use App\Entity\ProjectMembers;
use App\Entity\User;
use App\Service\Projet\ProjectMembersService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjouterMembreController extends AbstractController
{
    private $projectMembersService;
    private $entityManager;

    public function __construct(ProjectMembersService $projectMembersService, EntityManagerInterface $entityManager)
    {
        $this->projectMembersService = $projectMembersService;
        $this->entityManager = $entityManager;
    }

    #[Route('/ajouter-membre', name: 'ajouter_membre')]
    public function index(Request $request): Response
    {
        // Vérifiez si la requête est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            // Récupérez l'adresse e-mail envoyée par l'utilisateur
            $email = $request->request->get('email');

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user !== null) {
                $project = $this->entityManager->getRepository(Project::class)->findOneBy([], ['id' => 'DESC']);
                if ($project !== null) {
                    $projectMember = new ProjectMembers();
                    $projectMember->addStudent($user);
                    $projectMember->setProject($project);
                    $projectMember->setJoinedAt(new \DateTime());
                    $this->entityManager->persist($projectMember);
                    $this->entityManager->flush();

                    // Préparez la réponse JSON
                    $response = [
                        'exists' => true, // L'e-mail existe et le membre a été ajouté
                        'email' => $email // Adresse email du membre ajouté
                    ];

                    // Renvoyez la réponse JSON
                    return new JsonResponse($response);
                } else {
                    // Aucun projet trouvé, renvoyer une erreur
                    return new JsonResponse(['error' => 'Aucun projet trouvé'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                // Aucun utilisateur trouvé avec cet e-mail
                // Préparez la réponse JSON
                $response = [
                    'exists' => false // L'e-mail n'existe pas
                ];

                // Renvoyez la réponse JSON
                return new JsonResponse($response);
            }
        }

        // Si ce n'est pas une requête AJAX, affichez simplement la page
        return $this->render('/Project/AjouterMembres.html.twig');
    }
}