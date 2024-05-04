<?php

namespace App\Service\ActivitesServices;

use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripeClientFactory
{
    private $apiKey;

    public function __construct(ParameterBagInterface $params)
    {
        // Récupérer la valeur de la clé API à partir des variables d'environnement
        $this->apiKey = $params->get('STRIPE_SECRETKEY');
    }

    public function create(): StripeClient
    {
        // Passer la clé API lors de la création de l'objet StripeClient
        return new StripeClient($this->apiKey);
    }
}


