<?php

namespace App\Controller\UserController;

use App\Entity\PasswordResetRequest;
use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService\MailSender;
use App\Service\UserService\PasswordResetRequestService;
use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Uid\Uuid;

class SignUpController extends AbstractController
{

    private $userService;
    private $codeService;
    private $mailSender;

    public function __construct(PasswordResetRequestService $codeService,UserService $userService,MailSender $mailSender)
    {
        $this->codeService = $codeService;
        $this->userService = $userService;
        $this->mailSender = $mailSender;
    }

    #[Route('/SignUp', name: 'signUp')]
    function SignUp (){
        return $this->render('SignUp.html.twig');
    }

    #[Route('/addTeacher', name: 'addTeacher', methods: ["POST"])]
    public function addTeacher(Request $request, SessionInterface $session) {

        $teacherData = $session->get('teacherData');
        if ($teacherData) {
            $session->remove('teacherData');
        }
            $teacherData = [
                'nom' => $request->request->get('nom'),
                'prenom' => $request->request->get('prenom'),
                'genre' => $request->request->get('genre'),
                'dateNaissance' => $request->request->get('dateNaissance')
            ];

            $fileName = "";
            $file = $request->files->get('profil_picture');
            if ($file) {
                $uuid = Uuid::v4();
                $fileName = $uuid . '.' . $file->guessExtension();

                try {
                    $file->move(
                        'C:\xampp\htdocs\img',
                        $fileName
                    );
                } catch (FileException $e) {
                    throw new \Exception("There was an error adding the student: " . $e->getMessage());
                }

            
            }
            $teacherData['profilPicture'] = $fileName;
            $session->set('teacherData', $teacherData);

            return $this->render('ValidationPage.html.twig');
    }

    #[Route('/sendMail', name: 'sendMail', methods: ['POST'])]
    public function sendMail(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        try {
            $email = $data['email'] ?? null;
    
            $code = rand(10000, 99999);

            $this->mailSender->sendEmail($email, 'Vérification code', 'Salut ! Votre code de vérification est : ' . $code);

            $pr = new PasswordResetRequest(); 
            $pr->setResetCode($code);
            $expires = new \DateTime();
            $expires->add(new \DateInterval('PT15M'));
            $pr->setExpiresAt($expires);
            $pr->setUser(null);
            $this->codeService->add($pr);

            return $this->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Failed to send email: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/saveTeacher', name: 'saveTeacher', methods: ["POST"])]
    public function saveTeacher(Request $request, SessionInterface $session) {

        $teacherData = $session->get('teacherData');
        if (!$teacherData) {
            return $this->redirectToRoute('Home');
        }

        $teacher = new User();
        $teacher->setNom($teacherData['nom']);
        $teacher->setPrenom($teacherData['prenom']);
        $teacher->setGenre($teacherData['genre']);
        $teacher->setDateNaissance(new \DateTimeImmutable($teacherData['dateNaissance']));
        if ($teacherData['profilPicture'] !== "") {
            $teacher->setProfilPicture($teacherData['profilPicture']);
        }
        $teacher->setEmail($request->request->get('email'));
        $teacher->setPassword($request->request->get('password'));
    
        $this->userService->addTeacher($teacher);

        $session->remove('teacherData');

        return $this->redirectToRoute('login');
    }

}