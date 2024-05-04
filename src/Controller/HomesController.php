<?php
// src/Controller/HomesController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomesController extends AbstractController
{
    #[Route("/homeS", name: "homeS")]
    public function home(): Response
    {
        // Code pour gérer la page d'accueil
        return $this->render('HomeS.html.twig');
    }
    #[Route("/home-student", name: "home-student")]
    public function homeSt(): Response
    {
        // Code pour gérer la page d'accueil
        return $this->render('/Project/student/HomeStudent.html.twig');
    }
}
