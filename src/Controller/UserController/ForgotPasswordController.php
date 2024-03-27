<?php

namespace App\Controller\UserController;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgotPassword',name:'forgotPassword')]
    function forgotPassword() {
    return $this->render('/ResetPassword.html.twig');
    }
}