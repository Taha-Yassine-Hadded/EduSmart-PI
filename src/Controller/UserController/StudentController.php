<?php

namespace App\Controller\UserController;


use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService\MailSender;
use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Uid\Uuid;

class StudentController extends AbstractController
{
    private $userService;
    private $mailSender;

    public function __construct(UserService $userService,MailSender $mailSender)
    {
        $this->userService = $userService;
        $this->mailSender = $mailSender;
    }

    #[Route('/addStudent', name: 'addStudent')]
    function addStudent (Request $request){
        $student = new User();
        $form=$this->createForm(UserType::class,$student)->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {
           
            $file = $form->get('profil_picture')->getData();
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

            $student->setProfilPicture($fileName);
        }

        $this->userService->addStudent($student);

        try {
            $this->mailSender->sendEmail($student->getEmail(), 'Hello', 'This is a test email.');
        }
         catch (\Exception $e) {
            throw new \Exception("There was an error adding the student: " . $e->getMessage());
    }

        }

        return $this->renderForm('AddStudent.html.twig' , ['f'=>$form] );
    }
}