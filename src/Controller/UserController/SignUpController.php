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

class SignUpController extends AbstractController
{

    #[Route('/SignUp', name: 'signUp')]
    function SignUp (){
        return $this->render('SignUp.html.twig');
    }

}