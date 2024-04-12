<?php

namespace App\Controller\EventControllers;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class ReactionController extends AbstractController
{
    #[Route('/test', name: 'feeds',methods: 'GET')]
    public function index(): Response
    {
       
              
         return $this->render('event_controllers/reaction/add.html.twig', [
        ]);
    }
}
