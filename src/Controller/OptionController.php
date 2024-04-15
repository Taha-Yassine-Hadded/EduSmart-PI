<?php

namespace App\Controller;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use App\Repository\QuestionRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OptionController extends AbstractController
{
    #[Route('/option', name: 'app_option')]
    public function index(): Response
    {
        return $this->render('option/index.html.twig', [
            'controller_name' => 'OptionController',
        ]);
    }
    #[Route('/option/{questionId}', name: 'app_option_for_evaluation')]
    public function questionsForEvaluation(OptionRepository $optionRepository, $questionId): Response
    {
        $option = $optionRepository->findBy(['question' => $questionId]);
        
        return $this->render('option/option_for_evaluation.html.twig', [
            'option' => $option,
            'questionid' =>$questionId
        ]);
    } 
    #[Route('/optionE/delete{id}', name: 'app_optionEdelete')]
    public function delete(ManagerRegistry $manager, OptionRepository $repo, $id): Response
    {
    $option = $repo->find($id);

    if ($option) {
        $em = $manager->getManager();
        $em->remove($option);
        $em->flush();
    } else {
        // Handle the case where the entity was not found, for example, redirect to an error page
        return $this->redirectToRoute('error_route');
    }
    return $this->redirectToRoute('app_option_for_evaluation', ['questionId' => $option->getQuestion()->getId()]);
}
#[Route('/optionE/modify/{id}', name: 'app_optionEModifier')]
public function modify(Request $request, ManagerRegistry $manager, OptionRepository $repo, $id): Response
{
    $option = $repo->find($id);
    $form = $this->createForm(OptionType::class, $option);
    $em = $manager->getManager();
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist changes to the database
        $em->persist($option);
        $em->flush();

        return $this->redirectToRoute('app_option_for_evaluation', ['questionId' => $option->getQuestion()->getId()]);
    }

    return $this->render('option/optionForm.html.twig', [
        'f' => $form->createView()
    ]);
}
#[Route('/optionE/add{id}', name: 'app_option_add')]
public function add(Request $request, ManagerRegistry $manager, QuestionRepository $repo, $id): Response
{
    $option = new Option();
    $form = $this->createForm(OptionType::class, $option);
    $em = $manager->getManager();
    $form->handleRequest($request);
    $errorOccurred = false;
    if ($form->isSubmitted() && $form->isValid()) {
        $evaluation = $repo->find($id);
        $option->setQuestion($evaluation);

        $isCorrect = $option->isIsCorrect();

        if ($isCorrect) {
            $existingCorrectOption = $repo->findCorrectOptionForQuestion($id);

            if ($existingCorrectOption !== null) {
                $errorOccurred = true;
            } else {
                $errorOccurred = false;
                $em->persist($option);
                $em->flush();
                return $this->redirectToRoute('app_option_for_evaluation', ['questionId' => $id]);
            }

    } 
    else {
        $errorOccurred = false;
        $em->persist($option);
        $em->flush();
        return $this->redirectToRoute('app_option_for_evaluation', ['questionId' => $id]);
    }
    
    }

    return $this->renderForm('option/optionForm.html.twig', [
        'f' => $form,
        'errorOccurred' => $errorOccurred
    ]);
}


}
