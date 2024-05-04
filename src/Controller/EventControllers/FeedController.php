<?php

namespace App\Controller\EventControllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Entity\Events;
use App\Entity\User;
use App\Entity\EventReactions;
use App\Entity\EventComments;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;


class FeedController extends AbstractController
{
    private $currentPort;
    private $userId;

    public function __construct(Security $security)
    {
        $this->userId = $security->getUser()->getId();
        if (!$this->userId) {
            throw new \RuntimeException('User not authenticated');
        }
    }

    #[Route('/feed', name: 'feeds',methods: 'GET')]
    public function index(EventsRepository $eventReporsitory): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->userId === null) {
            throw new \RuntimeException('User not defined for this port');
        }

        $user = $entityManager->getRepository(User::class)->find($this->userId);       
         return $this->render('event_controllers/feed/index.html.twig', [
            'events' => $eventReporsitory->findAll(),
            'user' => $user,
        ]);
    }
    #[Route('/feed/{id}/reaction', name: 'add_reaction', methods: ['POST'])]
    public function add(Request $request, Events $event): Response
    {
        $action = null;
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $reactionType = $data['reactionType'];
       

        // If the port is not 8000 or 8001, set user to null
        if ($this->userId === null) {
            throw new \RuntimeException('User not defined for this port');
        }

        $user = $entityManager->getRepository(User::class)->find($this->userId);    
        // Find the existing reaction for the user and event, if any
        $existingReaction = $entityManager->getRepository(EventReactions::class)->findOneBy([
            'user' => $user,
            'event' => $event,
        ]);
    
        if ($existingReaction) {
            // If the existing reaction type is the same as the request, delete it
            if ($existingReaction->getReactionType() === $reactionType) {
                $entityManager->remove($existingReaction);
                $entityManager->flush();
                $action = 0;
            } else {
                // If the existing reaction type is different, update it
                $existingReaction->setReactionType($reactionType);
                $entityManager->flush();
                $action = 2;
            }
        } else {
            // Create a new reaction
            $reaction = new EventReactions();
            $reaction->setEvent($event);
            $reaction->setUser($user);
            $reaction->setReactionType($reactionType);
            $reaction->setCreatedAt(new \DateTime());
    
            $entityManager->persist($reaction);
            $entityManager->flush();
            $action = 1;
        }
    
        return new JsonResponse(['message' => 'Reacted successfully','action' => $action], JsonResponse::HTTP_OK);
    }
    #[Route('/feed/{id}/comment', name: 'add_comment', methods: ['POST'])]
public function addComment(Request $request, Events $event): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Fetch the currently logged-in user (assuming you have user authentication)
    $user = $entityManager->getRepository(User::class)->find($this->userId);

    // Create a new comment entity
    $comment = new EventComments();
    $comment->setEvent($event);
    $comment->setUser($user);
    $comment->setCommentText($request->request->get('comment_text'));
    $comment->setCreatedAt(new \DateTime());

    // Persist the comment
    $entityManager->persist($comment);
    $entityManager->flush();

    // Redirect back to the feed page
    return $this->redirectToRoute('feeds');
}
#[Route('/feed/{id}/comment/{commentId}/edit', name: 'edit_comment', methods: ['GET', 'POST'])]
public function editComment(Request $request, Events $event, int $commentId): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $comment = $entityManager->getRepository(EventComments::class)->find($commentId);

    if (!$comment) {
        throw $this->createNotFoundException('Comment not found');
    }

    // Check if the current user is allowed to edit the comment (optional)

    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('feeds');
    }

    return $this->render('event_controllers/feed/edit_comment.html.twig', [
        'event' => $event,
        'comment' => $comment,
        'form' => $form->createView(),
    ]);
}

#[Route('/feed/{id}/comment/{commentId}/delete', name: 'delete_comment', methods: ['POST'])]
public function deleteComment(Request $request, Events $event, int $commentId): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $comment = $entityManager->getRepository(EventComments::class)->find($commentId);

    if (!$comment) {
        throw $this->createNotFoundException('Comment not found');
    }

    // Check if the current user is allowed to delete the comment (optional)

    $entityManager->remove($comment);
    $entityManager->flush();

    return $this->redirectToRoute('feeds');
}
#[Route('/feed/{eventId}/add-participant/{userId}', name: 'event_add_participant')]
    public function addParticipant($eventId, $userId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the Event entity
        $event = $entityManager->getRepository(Events::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Retrieve the User entity
        $user = $entityManager->getRepository(User::class)->find($this->userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Add the user to the event's list of participants
        $event->addParticipant($user);

        // Persist the changes to the database
        $entityManager->flush();

        return new JsonResponse(['message' => 'Participant added successfully'], JsonResponse::HTTP_OK);
    }
    #[Route('/feed/{eventId}/remove-participant/{userId}', name: 'event_remove_participant')]
    public function removeParticipant($eventId, $userId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        // Retrieve the Event entity
        $event = $entityManager->getRepository(Events::class)->find($eventId);
    
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
    
        // Retrieve the User entity
        $user = $entityManager->getRepository(User::class)->find($this->userId);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        // Remove the user from the event's list of participants
        $event->removeParticipant($user);
    
        // Persist the changes to the database
        $entityManager->flush();
    
        // Return a JSON response indicating success
        return new JsonResponse(['message' => 'Participant removed successfully'], JsonResponse::HTTP_OK);
    }
     #[Route('/feed/{id}', name: 'event_show_one',methods: ['GET'])]
     public function showEvent(Events $event): Response
     {
        $entityManager = $this->getDoctrine()->getManager();
       
    
        
        if ($this->userId === null) {
            throw new \RuntimeException('User not defined for this port');
        }

        $user = $entityManager->getRepository(User::class)->find($this->userId);         
        return $this->render('event_controllers/feed/show.html.twig', [
             'event' => $event,
             'user' => $user,
         ]);
     }
}
