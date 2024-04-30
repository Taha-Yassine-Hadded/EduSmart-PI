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
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;


class AjouterMembreController extends AbstractController
{
    private $projectMembersService;
    private $entityManager;
    private $mailer; // Ajoutez la propriété pour le service MailerInterface




    public function __construct(ProjectMembersService $projectMembersService, EntityManagerInterface $entityManager, MailerInterface $mailer )
    {
        $this->projectMembersService = $projectMembersService;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer; // Injectez le service MailerInterface


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
                $nom = $user->getNom();

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

                    $email = (new Email())
                        ->from('espritedusmart@gmail.com')
                        ->to($email)
                        ->subject('Vous avez été ajouté à un nouveau projet')
                        ->html('
        <p style="color: #333; font-size: 16px; font-family: Arial, sans-serif;">Bonjour, '. $nom .'</p>
        <p style="color: #333; font-size: 16px; font-family: Arial, sans-serif;">Vous avez été ajouté à un nouveau projet.</p>
        <p style="color: #333; font-size: 16px; font-family: Arial, sans-serif;">Cordialement,<br>L\'équipe de Esprit-EduSmart</p>
    ');
                    $this->mailer->send($email);
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