<?php

namespace App\Controller\ActivitesController;

use App\Entity\Activite;
use App\Entity\Inscription;
use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface; 
use TCPDF;
use Twilio\Rest\Client;



#[Route('/inscrire')]
class InscrireController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private StripeClient $gateway;
    private SessionInterface $session; // Ajout de l'injection de dépendance pour SessionInterface

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->gateway = new StripeClient($_ENV['STRIPE_SECRETKEY']);
        $this->session = $session;
    }

    #[Route('/new/{id_activite}', name: 'app_inscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $id_activite): Response
{
    $inscription = new Inscription();

    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();
    // Récupérer la valeur de selected_price à partir des données du formulaire
    // Récupérer la valeur de selected_price à partir des données du formulaire (POST)
    $selectedPrice = $request->request->get('selected_price');
    


    



    // Pré-remplir les champs du formulaire avec les données de l'utilisateur connecté s'il existe
    if ($user !== null) {
        $inscription->setNom($user->getNom());
        $inscription->setPrenom($user->getPrenom());
        $inscription->setEmail($user->getEmail());
    }

    // Ajouter l'activité à l'inscription
    $activite = $this->getDoctrine()->getRepository(Activite::class)->find($id_activite);
    $inscription->setActivite($activite);

    $form = $this->createForm(InscriptionType::class, $inscription);
    $form->handleRequest($request);
    // Affichez ou imprimez les données du formulaire pour le débogage
    dump($request->request->all());

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'ID de l'activité à partir de la requête
        $activiteId = $request->request->get('activite_id');

        // Récupérer l'ID de l'utilisateur connecté depuis la requête
        $userId = $request->request->get('user_id');
        

        // Associer l'activité à l'inscription
        $activite = $this->getDoctrine()->getRepository(Activite::class)->find($activiteId);
        $inscription->setActivite($activite);
       

        // Associer l'utilisateur à l'inscription
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $inscription->setEtudiant($user);

        $this->entityManager->persist($inscription);
        $this->entityManager->flush();
        // Envoyer un SMS via Twilio
        $twilioSID = $_ENV['TWILIO_SID'];
        $twilioToken = $_ENV['TWILIO_AUTH_TOKEN'];
        $twilioNumber = $_ENV['TWILIO_PHONE_NUMBER'];

        $client = new Client($twilioSID, $twilioToken);
        $client->messages->create(
        $inscription->getNumTel(), // Numéro de téléphone du destinataire
        [
           'from' => $twilioNumber,
           'body' => "Félicitations pour votre pré-inscription ! Vous pouvez terminer le processus d'inscription par paiement en ligne ou sur place. Vous êtes les bienvenus."
        ]
        );
        // Générer le contenu du reçu PDF
        $receiptContent = "
        <h1>Reçu de pré-inscription</h1>
        <h2>Informations sur l'utilisateur :</h2>
        <p><strong>Nom :</strong> {$inscription->getNom()}</p>
        <p><strong>Prénom :</strong> {$inscription->getPrenom()}</p>
        <p><strong>Email :</strong> {$inscription->getEmail()}</p>
        <p><strong>Numéro de téléphone :</strong> {$inscription->getNumTel()}</p>
        <h2>Activité inscrite :</h2>
        <p><strong>Nom de l'activité :</strong> {$activite->getNom()}</p>
    ";

    // Créer une instance TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Paramètres du document PDF
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Receipt');
    $pdf->SetSubject('Receipt');
    $pdf->setFontSubsetting(true);
    $pdf->SetFont('helvetica', '', 11);

    // Ajouter une page
    $pdf->AddPage();

    // Écrire le contenu HTML dans le PDF
    $pdf->writeHTML($receiptContent);

    // Télécharger le PDF
    $pdfData = $pdf->Output('receipt.pdf', 'S');

    // Créer une réponse avec le PDF en pièce jointe
    $response = new Response($pdfData);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="receipt.pdf"');

    return $response;
    



    }

    // Définir activiteId à $id_activite
    $activiteId = $id_activite;

    return $this->renderForm('inscrire/index.html.twig', [
    'inscription' => $inscription,
    'form' => $form,
    'activiteId' => $activiteId,
    'selectedPrice' => $selectedPrice, // Passer selectedPrice au template Twig
]);

}




     /**
     * @throws ApiErrorException
     */
    #[Route('/checkout', name: 'app_checkout', methods: ['POST'])]
    public function checkout(Request $request): Response
    {
        // Récupérer le prix sélectionné 
        $selectedPrice = $request->request->get('selected_price');
    
        try {
            Stripe::setApiKey($_ENV['STRIPE_SECRETKEY']);
    
            $checkout = $this->gateway->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $_ENV['STRIPE_CURRENCY'],
                        'product_data' => [
                            'name' => 'Paiement pour l\'activité',
                        ],
                        'unit_amount' => 3000, // Mettez à jour le montant en centimes

                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->urlGenerator->generate('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->urlGenerator->generate('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
    
            // Rediriger vers l'URL de paiement Stripe
            return $this->redirect($checkout->url);
        } catch (ApiErrorException $e) {
            // Gérer les erreurs Stripe ici
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    




    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('success/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('cancel/cancel.html.twig', []);
    }

}
