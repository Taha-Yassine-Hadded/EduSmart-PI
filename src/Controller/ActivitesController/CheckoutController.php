<?php
namespace App\Controller\ActivitesController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout/payment", name="checkout_payment")
     */
    public function payment(): Response
    {
        return $this->render('checkout/payment.html.twig');
    }
}
