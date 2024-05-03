<?php

namespace App\Controller\UserController;

use App\Entity\FollowNotification;
use App\Repository\FollowNotificationRepository;
use App\Service\UserService\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Pusher\Pusher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route('/profile')]
class ProfilController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/',name:'myProfile')]
    public function myProfile(Security $security, UserService $userService, FollowNotificationRepository $notificationRepository) {
        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $unseenCount = $notificationRepository->countUnseenNotifications($user);

        return $this->render('/profiles/Profile.html.twig', [
            'user' => $user,
            'unseenNotifications' => $unseenCount
        ]);
    }

    #[Route('/user/{id}',name:'visitProfile')]
    public function visitProfile(Security $security, UserService $userService, $id) {

            $userToVisit = $userService->getUserById($id);
            $userLoggedIn = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

            if (!$userLoggedIn) {
                return $this->redirectToRoute('login');
            }


            if ($userLoggedIn->getFollowing() != null && $userLoggedIn->getFollowing()->contains($userToVisit)) {
                if($userLoggedIn == $userToVisit) {
                    $infos = ['pusher_key' => $_ENV['PUSHER_KEY'], 'pusher_cluster' => $_ENV['PUSHER_CLUSTER'], 'user' => $userToVisit, 'following' => 'true', 'me' => 'true'];
                } else {
                    $infos = ['pusher_key' => $_ENV['PUSHER_KEY'], 'pusher_cluster' => $_ENV['PUSHER_CLUSTER'], 'user' => $userToVisit, 'following' => 'true', 'me' => 'false'];
                }
            } else {
                if($userLoggedIn == $userToVisit) {
                    $infos = ['pusher_key' => $_ENV['PUSHER_KEY'], 'pusher_cluster' => $_ENV['PUSHER_CLUSTER'], 'user' => $userToVisit, 'following' => 'false', 'me' => 'true'];
                } else {
                    $infos = ['pusher_key' => $_ENV['PUSHER_KEY'], 'pusher_cluster' => $_ENV['PUSHER_CLUSTER'], 'user' => $userToVisit, 'following' => 'false', 'me' => 'false'];
                }
            }

            

            return $this->render(
                    '/profiles/VisitProfile.html.twig',
                    $infos
                );

    }

    #[Route('/follow-user/{id}', name: 'followUser')]
    public function followUser(Security $security, UserService $userService, EntityManagerInterface $entityManager, Pusher $pusher, $id) : Response {
        $userToFollow = $userService->getUserById($id);
        $userLoggedIn = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

        if (!$userLoggedIn) {
            return $this->redirectToRoute('login');
        }

        $userLoggedIn->addFollowing($userToFollow);
        $userToFollow->addFollower($userLoggedIn);

        // Create a new notification for the user being followed
        $notification = new FollowNotification();
        $notification->setUser($userToFollow); // the user who receives the notification
        $notification->setFollower($userLoggedIn);
        $notification->setContent($userLoggedIn->getPrenom() . ' ' . $userLoggedIn->getNom() . ' a commencé à vous suivre !');

        // Persist the new notification along with user follow relations
        $entityManager->persist($userLoggedIn);
        $entityManager->persist($userToFollow);
        $entityManager->persist($notification);
        $entityManager->flush();

        $data = [
            'message' => 'Bonne nouvelle ' . $userToFollow->getNom() . ' : ' . $notification->getContent(),
            'from' => $userLoggedIn->getPrenom() . ' ' . $userLoggedIn->getNom(),
            'to' => $id
        ];

        $pusher->trigger('my-channel', 'new-follow', $data);

        return $this->redirectToRoute('visitProfile', ['id' => $id]);
            
    }

    #[Route('/unfollow-user/{id}', name: 'unfollowUser')]
    public function unfollowUser(Security $security, UserService $userService, EntityManagerInterface $entityManager, $id): Response {

        $userToUnfollow = $userService->getUserById($id);
        $userLoggedIn = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

        if (!$userLoggedIn) {
            return $this->redirectToRoute('login');
        }
    
        if ($userLoggedIn->getFollowing()->contains($userToUnfollow)) {
            $userLoggedIn->removeFollowing($userToUnfollow);
            $userToUnfollow->removeFollower($userLoggedIn);
    
            $entityManager->persist($userLoggedIn);
            $entityManager->persist($userToUnfollow);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('visitProfile', ['id' => $id]);
    
    }
    
    /**
     * @Route("/getProfilePicture/{nomFile}", name="get_profile_picture")
     */
    public function getPhoto(string $nomFile): Response
    {
        $filePath = 'C:\\xampp\\htdocs\\img\\' . $nomFile;  // Ensure the path construction is correct

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException("The image does not exist.");
        }

        // Guess the MIME type based on the file extension
        $mimeTypes = new MimeTypes();
        $contentType = $mimeTypes->guessMimeType($filePath);

        return new BinaryFileResponse($filePath, 200, ['Content-Type' => $contentType]);
    }

    /**
     * @Route("/update-picture", name="update_profile_picture", methods={"POST"})
    */
    public function updateProfilePicture(Request $request, Security $security , UserService $userService): JsonResponse
    {
        $file = $request->files->get('profil_picture');
        if ($file) {
            $uuid = Uuid::v4();
            $fileName = $uuid . '.' . $file->guessExtension();

            // Move the file to the directory where profile pictures are stored
            try {
                $file->move(
                    'C:\xampp\htdocs\img',
                    $fileName
                );
            } catch (FileException $e) {
                throw new \Exception("There was an error adding the student: " . $e->getMessage());
            }

            // Assuming you have a method to set the filename in your user entity
            $email = $security->getUser()->getUserIdentifier();
            $user = $userService->getUserByEmail($email);
            $user->setProfilPicture($fileName);
            $this->em->persist($user);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'fileName' => $fileName,
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'No file was uploaded.'
        ]);
    }


    #[Route('/changePasswordUser',name:'changePasswordUser')]
    public function changePasswordUser(Security $security, UserService $userService, FollowNotificationRepository $notificationRepository) {
        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $unseenCount = $notificationRepository->countUnseenNotifications($user);

        return $this->render('/profiles/ProfileChangePassword.html.twig', ['user' => $user, 'unseenNotifications' => $unseenCount]);
    
    }

    #[Route('/savePasswordUser',name:'savePasswordUser')]
    public function savePasswordUser(Request $request,Security $security, UserService $userService) {

        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');

        if($password === $confirmPassword) {
            $userService->changePassword($password,$user);
            return $this->redirectToRoute('myProfile');
        }
        

        return $this->render('/profiles/ProfileChangePassword.html.twig');
        
    }

    /**
     * @Route("/delete-picture", name="profile_delete_picture", methods={"GET"})
    */
    public function deleteProfilePicture(Security $security, UserService $userService)
    {
        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());
        
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $user->setProfilPicture(null);
        $this->em->persist($user);
        $this->em->flush();

            return $this->redirectToRoute('myProfile');
    }

    #[Route('/notifications', name: 'user_notifications')]
    public function notifications(UserService $userService, FollowNotificationRepository $notificationRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        // Retrieve all notifications
        $notifications = $notificationRepository->findByUserOrderedByDateDesc($user);

        // Render the page with current notification statuses
        $response = $this->render('profiles/Notifications.html.twig', [
            'notifications' => $notifications,
            'user' => $user
        ]);

        // After rendering, mark notifications as seen
        foreach ($notifications as $notification) {
            if (!$notification->isSeen()) {
                $notification->setSeen(true);
            }
        }
        $entityManager->flush();

        return $response;
    }


}