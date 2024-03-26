<?php

namespace App\Controller\UserController;

use App\Entity\User;
use App\Entity\ValidationCode;
use App\Service\UserService\MailSender;
use App\Service\UserService\UserService;
use App\Service\UserService\ValidationCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __construct(ValidationCodeService $codeService,UserService $userService,MailSender $mailSender)
    {
        $this->codeService = $codeService;
        $this->userService = $userService;
        $this->mailSender = $mailSender;

    }

    #[Route('/SignUp', name: 'signUp')]
    function SignUp (SessionInterface $session){

        $studentData = $session->get('studentData');
        $teacherData = $session->get('teacherData');

        if ($teacherData) {
            $session->remove('teacherData');
        }

        if ($studentData) {
            $session->remove('studentData');
        }

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

    #[Route('/addStudent', name: 'addStudent', methods: ["POST"])]
    public function addStudent(Request $request, SessionInterface $session) {

        $studentData = $session->get('studentData');
        if ($studentData) {
            $session->remove('studentData');
        }
            $studentData = [
                'nom' => $request->request->get('nomStudent'),
                'prenom' => $request->request->get('prenomStudent'),
                'genre' => $request->request->get('genreStudent'),
                'dateNaissance' => $request->request->get('dateNaissanceStudent'),
                'classe' => $request->request->get('calsse'),
                'niveau' => $request->request->get('niveau'),
                'cin' => $request->request->get('cin')
            ];

            $fileName = "";
            $file = $request->files->get('profil_pictureStudent');
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
            $studentData['profilPictureStudent'] = $fileName;
            $session->set('studentData', $studentData);

            return $this->render('ValidationPage.html.twig');
    }

    #[Route('/addEntreprise', name: 'addEntreprise', methods: ['POST'])]
    public function addEntreprise(Request $request)
    {
        $enterprise = new User();
        
        $enterprise->setNom($request->request->get('nomEntreprise'));
        $enterprise->setPays($request->request->get('pays'));
        $enterprise->setLocalisation($request->request->get('local'));
        $enterprise->setWebsite($request->request->get('website'));
        $enterprise->setEmail($request->request->get('email'));
        $enterprise->setPassword($request->request->get('password'));

        $this->userService->addEntreprise($enterprise);

        return $this->redirectToRoute('login');
    }

    #[Route('/sendMail', name: 'sendMail', methods: ['POST'])]
    public function sendMail(Request $request): Response {


        $data = json_decode($request->getContent(), true);

            $email = $data['email'] ?? null;

            if ($this->userService->getUserByEmail($email) === null) {

                $code = rand(10000, 99999);

                $this->mailSender->sendEmail($email, 'Vérification code', 'Salut ! Votre code de vérification est : ' . $code);

                $pr = new ValidationCode(); 
                $pr->setResetCode($code);
                $expires = new \DateTime();
                $expires->add(new \DateInterval('PT15M'));
                $pr->setExpiresAt($expires);

                $validationCode = $this->codeService->add($pr);

                return $this->json(['status' => 'success', 'message' => 'Email sent successfully', 'id' => $validationCode->getId(), 'exist' => 'false']);

            } 

            else {
                return $this->json(['exist' => 'true']);
            }
    
    }

    #[Route('/verifyCode', name: 'verifyCode', methods: ['POST'])]
    public function verifyCode(Request $request): Response {

        $data = json_decode($request->getContent(), true);
        $submittedCode = $data['code'] ?? null;
        $id = $data['id'] ?? null;
    
        $passwordResetRequest = $this->codeService->getById($id);
    
        if ($passwordResetRequest->getResetCode() == $submittedCode) {

            $this->codeService->delete($id); 
            return $this->json(['verified' => true]);
    
        }

        return $this->json(['verified' => false]);
        
    }

    #[Route('/deleteCode', name: 'deleteCode', methods: ['DELETE'])]
    public function deleteCode(Request $request): void {

        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;
    
        $this->codeService->delete($id);

    }


    #[Route('/saveTeacher', name: 'saveTeacher', methods: ["POST"])]
    public function saveTeacher(Request $request, SessionInterface $session) {

        $teacherData = $session->get('teacherData');
        $studentData = $session->get('studentData');

        if ($teacherData) {
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
        }

        if ($studentData) {
            $student = new User();
            $student->setNom($studentData['nom']);
            $student->setPrenom($studentData['prenom']);
            $student->setGenre($studentData['genre']);
            $student->setCin($studentData['cin']);
            $student->setNiveau($studentData['niveau']);
            $student->setClasse($studentData['classe']);
            $student->setDateNaissance(new \DateTimeImmutable($studentData['dateNaissance']));
            if ($studentData['profilPictureStudent'] !== "") {
                $student->setProfilPicture($studentData['profilPictureStudent']);
            }
            $student->setEmail($request->request->get('email'));
            $student->setPassword($request->request->get('password'));
        
            $this->userService->addStudent($student);

            $session->remove('studentData');
        }

        return $this->redirectToRoute('login');
    }

}