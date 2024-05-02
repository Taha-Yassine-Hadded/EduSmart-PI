<?php

namespace App\Controller\UserController;

use App\Service\UserService\UserService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Notifier\Message\NullMessage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
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
    public function myProfile(Security $security, UserService $userService) {
        $user = $userService->getUserByEmail($email = $security->getUser()->getUserIdentifier());
        
        return $this->render(
            '/profiles/Profile.html.twig',
            ['user' => $user]
        );
    }

    #[Route('/user/{id}',name:'visitProfile')]
    public function visitProfile(Security $security, UserService $userService, $id) {

            $userToVisit = $userService->getUserById($id);
            $userLoggedIn = $userService->getUserByEmail($security->getUser()->getUserIdentifier());
            $nbFollowers = sizeof($userToVisit->getFollowers());
            $nbFollowing = sizeof($userToVisit->getFollowing());


            if ($userLoggedIn->getFollowing() != null && $userLoggedIn->getFollowing()->contains($userToVisit)) {
                if($userLoggedIn == $userToVisit) {
                    $infos = ['user' => $userToVisit, 'following' => 'true', 'me' => 'true', 'nbFollowers' => $nbFollowers , 'nbFollowing' => $nbFollowing ];
                } else {
                    $infos = ['user' => $userToVisit, 'following' => 'true', 'me' => 'false', 'nbFollowers' => $nbFollowers , 'nbFollowing' => $nbFollowing ];
                }
            } else {
                if($userLoggedIn == $userToVisit) {
                    $infos = ['user' => $userToVisit, 'following' => 'false', 'me' => 'true', 'nbFollowers' => $nbFollowers , 'nbFollowing' => $nbFollowing ];
                } else {
                    $infos = ['user' => $userToVisit, 'following' => 'false', 'me' => 'false', 'nbFollowers' => $nbFollowers , 'nbFollowing' => $nbFollowing ];
                }
            }

            return $this->render(
                    '/profiles/VisitProfile.html.twig',
                    $infos
                );

    }

    #[Route('/follow-user/{id}', name: 'followUser')]
    public function followUser(Security $security, UserService $userService, EntityManagerInterface $entityManager, $id) : Response {
        $userToFollow = $userService->getUserById($id);
        $userLoggedIn = $userService->getUserByEmail($security->getUser()->getUserIdentifier());

        $userLoggedIn->addFollowing($userToFollow);
        $userToFollow->addFollower($userLoggedIn);

        $entityManager->persist($userLoggedIn);
        $entityManager->persist($userToFollow);
        $entityManager->flush();

        return $this->redirectToRoute('visitProfile', ['id' => $id]);

    }

    #[Route('/unfollow-user/{id}', name: 'unfollowUser')]
    public function unfollowUser(Security $security, UserService $userService, EntityManagerInterface $entityManager, $id): Response {

        $userToUnfollow = $userService->getUserById($id);
        $userLoggedIn = $userService->getUserByEmail($security->getUser()->getUserIdentifier());
    
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
    public function changePasswordUser(Security $security, UserService $userService) {
        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());
        return $this->render('/profiles/ProfileChangePassword.html.twig', ['user' => $user]);
    
    }

    #[Route('/savePasswordUser',name:'savePasswordUser')]
    public function savePasswordUser(Request $request,Security $security, UserService $userService) {

        $user = $userService->getUserByEmail($security->getUser()->getUserIdentifier());
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
        $user->setProfilPicture(null);
        $this->em->persist($user);
        $this->em->flush();

            return $this->redirectToRoute('myProfile');
    }
}