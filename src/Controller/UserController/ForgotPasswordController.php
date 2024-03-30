<?php

namespace App\Controller\UserController;

use App\Entity\ResetPasswordToken;
use App\Service\UserService\MailSender;
use App\Service\UserService\ResetPasswordTokenService;
use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordController extends AbstractController
{

    private $userService;
    private $tokenService;
    private $mailSender;

    public function __construct(ResetPasswordTokenService $tokenService, UserService $userService ,MailSender $mailSender)
    {
        $this->tokenService = $tokenService;
        $this->userService = $userService;
        $this->mailSender = $mailSender;

    }


    #[Route('/forgotPassword',name:'forgotPassword')]
    function forgotPassword() {
    return $this->render('/ForgotPassword.html.twig');
    }

    #[Route('/sendResetMail', name: 'sendResetMail', methods: ['POST'])]
    public function sendResetMail(Request $request, UrlGeneratorInterface $urlGenerator): Response {

        $email = $request->request->get('email');

            if ($this->userService->getUserByEmail($email) != null) {

                $tokenValue = bin2hex(random_bytes(30));

                $resetPasswordLink = $urlGenerator->generate('resetPassword', ['token' => $tokenValue], UrlGeneratorInterface::ABSOLUTE_URL);

                $content = "<p>Bonjour,</p>"
                . "<p>Vous avez demandé la réinitialisation de votre mot de passe</p>"
                . "<p>Cliquez sur le lien ci-dessous pour changer votre mot de passe</p>"
                . "<p><b><a href=\"" . $resetPasswordLink . "\">Changer mon mot de passe</a></b></p>"
                . "<p>Ignorez cet e-mail si vous vous souvenez du mot de passe ou si vous n'avez pas fait la demande pour le changer</p>"
                . "<p style='color : red;'><b>Le lien expire dans 15 minutes !</b></p>";


                $this->mailSender->sendEmail($email, 'Voici le lien pour réinitialiser votre mot de passe', $content);

                $token = new ResetPasswordToken(); 
                $token->setToken($tokenValue);
                $token->setUser($this->userService->getUserByEmail($email));
                $expires = new \DateTime();
                $expires->add(new \DateInterval('PT15M'));
                $token->setExpiresAt($expires);

                $this->tokenService->add($token);

                $this->addFlash('success', 'Nous enverrons un lien de réinitialisation du mot de passe à votre adresse e-mail');

                return $this->redirectToRoute('forgotPassword');

            } 

            else {

                $this->addFlash('error', 'Impossible de trouver un utilisateur avec cet e-mail !');
                return $this->redirectToRoute('forgotPassword');

            }
    
    }

    #[Route('/resetPassword', name: 'resetPassword')]
    public function resetPassword(Request $request): Response
    {
        $token = $request->query->get('token');
        $user = $this->tokenService->getByToken($token)->getUser();

        if (!$user) {
            if ( new \DateTime() >= $this->tokenService->getByToken($token)->getExpiresAt()) {
                // Token expired
                $message = "Token expiré !";
                return $this->render('ForgotPassword.html.twig', ['message' => $message]);
            } else {
                // Token is invalid
                $message = "Token invalid !";
                return $this->render('ForgotPassword.html.twig', ['message' => $message]);
            }
        }
        return $this->render('ResetPassword.html.twig', ['token' => $token]);
}



    #[Route('/changePassword', name: 'changePassword', methods: ['POST'])]
    public function changePassword(Request $request): Response
    {
        $token = $request->request->get('token');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');

        if($password === $confirmPassword) {
            $user = $this->userService->getUserByResetPassworToken($token);
            $this->userService->changePassword($password,$user);
            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('login');
    }


}