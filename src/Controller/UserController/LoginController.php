<?php

namespace App\Controller\UserController;

use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {   
        $lastUsername = $authenticationUtils->getLastUsername();

        $captchaFailed = $request->query->get('captcha_failed', false);

        if($authenticationUtils->getLastAuthenticationError()) {
            if ($authenticationUtils->getLastAuthenticationError() != null && $authenticationUtils->getLastAuthenticationError()->getMessageKey() === 'Votre compte pas désactivé.') {
                $error = 'Votre compte est pas désactivé. Veuillez contacter l\'administrateur.';
            } else {
                $error = 'Veuillez vérifier vos informations de connexion.';
            }   
        }elseif($captchaFailed) {
            $error = 'Veuillez vérifiez le CAPTCHA.';
        }else {
            $error = '';
        }

        return $this->render('Login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    #[Route('/logout', name : "logout", methods: ['POST','GET'])]
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}