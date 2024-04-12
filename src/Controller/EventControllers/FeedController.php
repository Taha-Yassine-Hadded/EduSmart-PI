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

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'feeds',methods: 'GET')]
    public function index(EventsRepository $eventReporsitory): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $currentPort = $_SERVER['SERVER_PORT'];
        
        // Fetch the user based on the port
        $userId = ($currentPort == '8000') ? 3 : (($currentPort == '8001') ? 4 : null);

        // If the port is not 8000 or 8001, set user to null
        if ($userId === null) {
            throw new \RuntimeException('User not defined for this port');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);       
         return $this->render('event_controllers/feed/index.html.twig', [
            'events' => $eventReporsitory->findAll(),
            'user' => $user,
        ]);
    }
    #[Route('/feed/{id}/reaction', name: 'add_reaction', methods: ['GET', 'POST'])]
    public function add(Request $request, Events $event): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reactionType = $request->query->get('reaction');
    
        // Find the user associated with the reaction
        $currentPort = $_SERVER['SERVER_PORT'];
        
        // Fetch the user based on the port
        $userId = ($currentPort == '8000') ? 3 : (($currentPort == '8001') ? 4 : null);

        // If the port is not 8000 or 8001, set user to null
        if ($userId === null) {
            throw new \RuntimeException('User not defined for this port');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);    
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
            } else {
                // If the existing reaction type is different, update it
                $existingReaction->setReactionType($reactionType);
                $entityManager->flush();
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
        }
    
        return $this->redirectToRoute('feeds', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/feed/{id}/comment', name: 'add_comment', methods: ['POST'])]
public function addComment(Request $request, Events $event): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Fetch the currently logged-in user (assuming you have user authentication)
    $user = $entityManager->getRepository(User::class)->find(4);

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
#[Route('/event/{eventId}/add-participant/{userId}', name: 'event_add_participant')]
    public function addParticipant($eventId, $userId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the Event entity
        $event = $entityManager->getRepository(Events::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Retrieve the User entity
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Add the user to the event's list of participants
        $event->addParticipant($user);

        // Persist the changes to the database
        $entityManager->flush();

        return $this->redirectToRoute('feeds');    
    }
    #[Route('/event/{eventId}/remove-participant/{userId}', name: 'event_remove_participant')]
    public function removeParticipant($eventId, $userId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the Event entity
        $event = $entityManager->getRepository(Events::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Retrieve the User entity
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Add the user to the event's list of participants
        $event->removeParticipant($user);

        // Persist the changes to the database
        $entityManager->flush();

        return $this->redirectToRoute('feeds');   
     }
     #[Route('/feed/{id}', name: 'event_show_one',methods: ['GET'])]
     public function showEvent(Events $event): Response
     {
        $entityManager = $this->getDoctrine()->getManager();
       
    
        // Find the user associated with the reaction
        $currentPort = $_SERVER['SERVER_PORT'];
        
        // Fetch the user based on the port
        $userId = ($currentPort == '8000') ? 3 : (($currentPort == '8001') ? 4 : null);

        // If the port is not 8000 or 8001, set user to null
        if ($userId === null) {
            throw new \RuntimeException('User not defined for this port');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);         return $this->render('event_controllers/feed/show.html.twig', [
             'event' => $event,
             'user' => $user,
         ]);
     }
}
