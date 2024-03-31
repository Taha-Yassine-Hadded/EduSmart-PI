<?php

namespace App\Controller\UserController;

use App\Entity\RoleEnum;
use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    #[Route('/showDashboard',name:'showDashboard')]
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
}