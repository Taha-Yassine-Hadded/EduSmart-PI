<?php

namespace App\Controller\EventControllers;

use App\Entity\Events;
use App\Entity\User;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\EventReactions;
class EventController extends AbstractController
{


    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    // Your controller actions...

    // Helper function to generate unique file names
    private function generateUniqueFileName(UploadedFile $file): string
    {
        // Generate a unique name for the file
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        return $fileName;
    }



    #[Route('/event', name: 'events',methods: 'GET')]
    public function index(EventsRepository $eventReporsitory): Response
    {
        return $this->render('event_controllers/event/index.html.twig', [
            'events' => $eventReporsitory->findAll(),
        ]);
    }

    #[Route('/event/new', name: 'event_new',methods: ['GET', 'POST'])]
    public function new(Request $request): Response
{
    $event = new Events();
    $form = $this->createForm(EventType::class, $event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $file = $form->get('event_photo')->getData();
        if ($file) {
            $fileName = $this->generateUniqueFileName($file);

            // Copy the file to the desired directory
            try {
                copy(
                    $file->getPathname(),
                    'C:\Users\GAMING\Desktop\pidevSymfony\PiSymfony\public\uploads\\' . $fileName
                );
            } catch (FileException $e) {
                // Handle file upload error
                // For example, return a default image or show an error message
            }

            // Set the filename to the entity property
            $event->setEventPhoto($fileName);
        }

        // Save the entity
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        // Redirect or return a response
        return $this->redirectToRoute('events', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('event_controllers/event/new.html.twig', [
        'event' => $event,
        'form' => $form->createView(),
    ]);
}
private const OPENAI_API_KEY = "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";
private const OPENAI_API_URL = "https://api.openai.com/v1/chat/completions";

#[Route('/event/{id}', name: 'event_show', methods: ['GET'])]
public function show(Events $event): Response
{
    // Get comments associated with the event
    $comments = $event->getEventComments();

    $commentTexts = [];
    foreach ($comments as $comment) {
    $commentTexts[] = $comment->getCommentText();
    }
    
    // Concatenate comments into a single string
    $concatenatedText = implode(' ',$commentTexts);
    
    // Build the prompt
    $prompt = "Please analyze the sentiment of these comments to gauge public opinion regarding the event.

    Provide a brief summary of the sentiment in one or two phrases.
    
    Additionally, offer suggestions for improvement based on any negative comments.
    
    If there are no comments, please indicate so.
    
    When providing suggestions for improvement, ensure to start each suggestion on a new line.
    dont forget to structure the response to bea clear by getting back to new lines
    " . $concatenatedText;
    
    // Build the request body
    $data = [
        "model" => "gpt-3.5-turbo-0125",
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt
            ]
        ]
    ];
    
    $body = json_encode($data);
    
    // Prepare the cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::OPENAI_API_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . self::OPENAI_API_KEY
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if($response === false) {
        echo 'Curl error: ' . curl_error($ch);
        return new Response('Error occurred');
    }

    // Close cURL session
    curl_close($ch);
    
    // Decode the response JSON
    $responseData = json_decode($response, true);
    echo '<script>console.log('.json_encode($responseData).')</script>';
    // Extract content from the response
    if (isset($responseData['choices'])) {
        // Extract content from the response
        $content = $responseData['choices'][0]['message']['content'];
    } else {
        // Handle the case when 'choices' key is not present
        $content = 'No sentiment analysis available.';
    }

    // Render the view
    return $this->render('event_controllers/event/show.html.twig', [
        'event' => $event,
        'analysis' => $content // Pass the sentiment analysis result to the view
    ]);
}

    #[Route('/event/{id}/edit', name: 'event_edit',methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('events', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event_controllers/event/edit.html.twig', [
            'project' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}', name: 'event_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Events $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('events', [], Response::HTTP_SEE_OTHER);
    }
  
    
}
