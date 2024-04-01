<?php

namespace App\Controller\UserController;

use App\Entity\RoleEnum;
use App\Entity\User;
use App\Form\EntrepriseType;
use App\Form\StudentType;
use App\Form\TeacherType;
use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    #[Route('/users',name:'showDashboard')]
    function showDashboard() {

        $roleCounts = $this->userService->getUserCountByRole();

        $studentCount = $roleCounts[RoleEnum::STUDENT->value] ?? 0;
        $teacherCount = $roleCounts[RoleEnum::TEACHER->value] ?? 0;
        $clubCount = $roleCounts[RoleEnum::CLUB->value] ?? 0;
        $entrepriseCount = $roleCounts[RoleEnum::ENTREPRISE->value] ?? 0;

        $teachersList = $this->userService->getUsersByRole(RoleEnum::TEACHER);

        $studentsList = $this->userService->getUsersByRole(RoleEnum::STUDENT);

        $entreprisesList = $this->userService->getUsersByRole(RoleEnum::ENTREPRISE);

        $clubsList = $this->userService->getUsersByRole(RoleEnum::CLUB);

        return $this->render('/Dashboard.html.twig', ['teachers' => $teachersList,'students' => $studentsList,
                             'entreprises' => $entreprisesList, 'clubs' => $clubsList,
                            'studentCount' => $studentCount,'teacherCount' => $teacherCount,
                            'clubCount' => $clubCount, 'entrepriseCount' => $entrepriseCount
                            ]);
    }

    #[Route('/deleteUser/{id}',name:'deleteUser')]
    function deleteUser($id){

        $this->userService->delete($id);
        
        return $this->redirectToRoute('showDashboard');
    }

    #[Route('/blockUser/{id}',name:'blockUser')]
    function blockUser($id){

        $this->userService->blockUser($id);
        
        return $this->redirectToRoute('showDashboard');
    }

    #[Route('/unblockUser/{id}',name:'unblockUser')]
    function unblockUser($id){

        $this->userService->unblockUser($id);
        
        return $this->redirectToRoute('showDashboard');
    }

    #[Route('/toRH/{id}',name:'toRH')]
    function toRH($id){

        $this->userService->toClubRH($id);
        
        return $this->redirectToRoute('showDashboard');
    }

    #[Route('/toStudent/{id}',name:'toStudent')]
    function toStudent($id){

        $this->userService->toStudent($id);
        
        return $this->redirectToRoute('showDashboard');
    }

    #[Route('/newTeacher',name:'newTeacher')]
    function newTeacher(Request $request){
        $teacher = new User();

        $form=$this->createForm(TeacherType::class,$teacher);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $teacher->setProfilPicture(null);

            $this->userService->addTeacher($teacher);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formTeacher.html.twig', ['f' => $form->createView(), 'is_edit' => false]);
    }

    #[Route('/newStudent',name:'newStudent')]
    function newStudent(Request $request){
        $student = new User();

        $form=$this->createForm(StudentType::class,$student);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $this->userService->addStudent($student);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formStudent.html.twig', ['f' => $form->createView(), 'is_edit' => false]);
    }

    #[Route('/newEntreprise',name:'newEntreprise')]
    function newEntreprise(Request $request){
        $entreprise = new User();

        $form=$this->createForm(EntrepriseType::class,$entreprise);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $this->userService->addEntreprise($entreprise);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formEntreprise.html.twig', ['f' => $form->createView(), 'is_edit' => false]);
    }

    #[Route('/editTeacher/{id}',name:'editTeacher')]
    function editTeacher(Request $request,$id){

        $teacher = $this->userService->getUserById($id);

        $form = $this->createForm(TeacherType::class, $teacher, ['include_password' => false]);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $this->userService->addTeacher($teacher);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formTeacher.html.twig', ['f' => $form->createView(), 'is_edit' => true]);
    }

    #[Route('/editStudent/{id}',name:'editStudent')]
    function editStudent(Request $request,$id){

        $student = $this->userService->getUserById($id);

        $form = $this->createForm(StudentType::class, $student, ['include_password' => false]);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $this->userService->addStudent($student);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formStudent.html.twig', ['f' => $form->createView(), 'is_edit' => true]);
    }

    #[Route('/editClub/{id}',name:'editClub')]
    function editClub(Request $request,$id){

        $student = $this->userService->getUserById($id);

        $form = $this->createForm(StudentType::class, $student, ['include_password' => false]);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $this->userService->addClub($student);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formStudent.html.twig', ['f' => $form->createView(), 'is_edit' => true]);
    }

    #[Route('/editEntreprise/{id}',name:'editEntreprise')]
    function editEntreprise(Request $request,$id){

        $entreprise = $this->userService->getUserById($id);

        $form = $this->createForm(EntrepriseType::class, $entreprise, ['include_password' => false]);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {

            $this->userService->addEntreprise($entreprise);
            
            return $this->redirectToRoute('showDashboard');
        }

        return $this->render('formEntreprise.html.twig', ['f' => $form->createView(), 'is_edit' => true]);
    }


    #[Route('/changePasswordAdmin',name:'changePasswordAdmin')]
    function changePasswordAdmin() {
        
        return $this->render('/DashboardChangePassword.html.twig');
    
    }

    #[Route('/savePasswordAdmin',name:'savePasswordAdmin')]
    function savePasswordAdmin(Request $request,Security $security) {

        $admin = $this->userService->getUserByEmail($security->getUser()->getUserIdentifier());
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');

        if($password === $confirmPassword) {
            $this->userService->changePassword($password,$admin);
            return $this->redirectToRoute('showDashboard');
        }
        

        return $this->render('/DashboardChangePassword.html.twig');
        
    }

}