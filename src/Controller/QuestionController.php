<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Evaluation;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\EvaluationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'app_question')]
    public function index(): Response
    {
        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }
    #[Route('/questions/{evaluationId}', name: 'app_questions_for_evaluation')]
    public function questionsForEvaluation(QuestionRepository $questionRepository, $evaluationId): Response
    {
        $questions = $questionRepository->findBy(['evaluation' => $evaluationId]);
        
        return $this->render('question/questions_for_evaluation.html.twig', [
            'questions' => $questions,
            'evaluationid' =>$evaluationId
        ]);
    }
      #[Route('/questionE/delete{id}', name: 'app_questionEdelete')]
    public function delete(ManagerRegistry $manager, QuestionRepository $repo, $id): Response
    {
    $questions = $repo->find($id);

    if ($questions) {
        $em = $manager->getManager();
        $em->remove($questions);
        $em->flush();
    } else {
        // Handle the case where the entity was not found, for example, redirect to an error page
        return $this->redirectToRoute('error_route');
    }
    return $this->redirectToRoute('app_questions_for_evaluation', ['evaluationId' => $questions->getEvaluation()->getId()]);
}

    #[Route('/questionE/modify/{id}', name: 'app_questionEModifier')]
public function modify(Request $request, ManagerRegistry $manager, QuestionRepository $repo, $id): Response
{
    $questions = $repo->find($id);
    $form = $this->createForm(QuestionType::class, $questions);
    $em = $manager->getManager();
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist changes to the database
        $em->persist($questions);
        $em->flush();

        return $this->redirectToRoute('app_questions_for_evaluation', ['evaluationId' => $questions->getEvaluation()->getId()]);
    }

    return $this->render('question/questionForm.html.twig', [
        'f' => $form->createView()
    ]);
}
#[Route('/questionE/add{id}', name: 'app_question_add')]
public function add(Request $request, ManagerRegistry $manager, EvaluationRepository $repo, $id): Response
{
   // $evaluation = new Evaluation();
    $questions=new Question();
   $form = $this->createForm(QuestionType::class,$questions);
   $em = $manager->getManager();
   $form->handleRequest($request);
   if($form->isSubmitted() && $form->isValid())
   {
    $evaluation=$repo->find($id);
    $questions->setEvaluation($evaluation);
    $em->persist($questions);
   $em->flush();
   return $this->redirectToRoute('app_questions_for_evaluation', ['evaluationId' => $id]);

   }
    return $this->renderForm('question/questionForm.html.twig',
     ['f' => $form]     
    );
}
}
