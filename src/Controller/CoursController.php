<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(CoursRepository $repo): Response
    {
        
        $list = $repo->findAll(); 
        return $this->render('cours/index.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/coursE', name: 'app_coursE')]
    public function indexE(CoursRepository $repo): Response
    {
        
        $list = $repo->findAll(); 
        return $this->render('cours/Enseignantindex.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/coursE/delete{id}', name: 'app_coursEdelete')]
    public function delete(ManagerRegistry $manager,CoursRepository $repo,$id): Response
    {
        $cours = $repo->find($id);
        $em = $manager->getManager();
        $em->remove($cours);
        $em->flush();
        return $this->redirectToRoute('app_coursE');
    }
#[Route('/coursE/modify/{id}', name: 'app_coursEModifier')]
public function modify(Request $request, ManagerRegistry $manager, CoursRepository $repo, $id): Response
{
    $cours = $repo->find($id);
    $form = $this->createForm(CoursType::class, $cours);
    $em = $manager->getManager();

    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $file = $form->get('coursURLpdf')->getData();
        if ($file) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            // Move the file to the directory where course files are stored
            try {
                $file->move(
                    $this->getParameter('cours_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // Handle file upload error
                // For example, you can log the error or show a flash message to the user
                $this->addFlash('error', 'An error occurred while uploading the file.');
                return $this->redirectToRoute('app_coursE');
            }
            // Update the coursURLpdf property to store the file name
            $cours->setCoursURLpdf($fileName);
        }

        // Persist changes to the database
        $em->persist($cours);
        $em->flush();

        return $this->redirectToRoute('app_coursE');
    }

    return $this->render('cours/coursForm.html.twig', [
        'f' => $form->createView()
    ]);
}

#[Route('/cours/add', name: 'app_cours_add')]
public function add(Request $req, ManagerRegistry $manager, UserRepository $repou): Response
{
    $cours = new Cours();
    $form = $this->createForm(CoursType::class, $cours);
    $em = $manager->getManager();
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $file = $form->get('coursURLpdf')->getData();
        if ($file) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            // Move the file to the directory where course files are stored
            try {
                $file->move(
                    $this->getParameter('cours_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // Handle file upload error
                // For example, you can log the error or show a flash message to the user
                $this->addFlash('error', 'An error occurred while uploading the file.');
                return $this->redirectToRoute('app_cours_add');
            }
            // Set the value of coursURLpdf property to the file name
            $cours->setCoursURLpdf($fileName);
        }

        // Set the teacher for the course (example: assuming teacher ID is 5)
        $teacher = $repou->find(5);
        $cours->setTeacher($teacher);

        // Persist the course entity
        $em->persist($cours);
        $em->flush();

        // Redirect to the course list page
        return $this->redirectToRoute('app_coursE');
    }

    return $this->render('cours/coursForm.html.twig', [
        'f' => $form->createView()
    ]);
}

    #[Route('/download-pdf/{id}', name: 'download_pdf')]
public function downloadPdf($id, CoursRepository $repo): Response
{
    $cours = $repo->find($id);
    if (!$cours) {
        throw $this->createNotFoundException('The course does not exist');
    }

    // Assuming the PDF file is stored in a directory accessible by the web server
    $pdfPath = $this->getParameter('cours_directory') . '/' . $cours->getCoursURLpdf();

    // Create the response object
    $response = new Response();

    // Set the headers to indicate that this is a PDF file download
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        $cours->getCoursURLpdf()
    ));

    // Set the content of the response to the contents of the PDF file
    $response->setContent(file_get_contents($pdfPath));

    return $response;
}
}
