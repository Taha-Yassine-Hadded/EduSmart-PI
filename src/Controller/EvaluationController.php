<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\OptionUser;
use App\Form\EvaluationType;
use App\Repository\UserRepository;
use App\Repository\CoursRepository;
use App\Repository\EvaluationRepository;
use App\Repository\OptionRepository;
use App\Repository\OptionUserRepository;
use App\Repository\QuestionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvaluationController extends AbstractController
{
    
    #[Route('/evaluation', name: 'app_evaluation')]
    public function index(): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'controller_name' => 'EvaluationController',
        ]);
    }
    #[Route('/evaluationE', name: 'app_evaluationE')]
    public function indexE(EvaluationRepository $repo): Response
    {
        
        $list = $repo->findAll(); 
        return $this->render('evaluation/Enseignantindex.html.twig', [
            'list' => $list
        ]);
    }
    #[Route('/evaluationE/delete{id}', name: 'app_evaluationEdelete')]
    public function delete(ManagerRegistry $manager,EvaluationRepository $repo,$id): Response
    {
        $evaluation = $repo->find($id);
        $em = $manager->getManager();
        $em->remove($evaluation);
        $em->flush();
        return $this->redirectToRoute('app_evaluationE');
    }
#[Route('/evaluationE/modify/{id}', name: 'app_evaluationEModifier')]
public function modify(Request $request, ManagerRegistry $manager, EvaluationRepository $repo, $id): Response
{
    $evaluation = $repo->find($id);
    $form = $this->createForm(EvaluationType::class, $evaluation);
    $em = $manager->getManager();
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist changes to the database
        $em->persist($evaluation);
        $em->flush();

        return $this->redirectToRoute('app_evaluationE');
    }

    return $this->render('evaluation/evaluationForm.html.twig', [
        'f' => $form->createView()
    ]);
}
#[Route('/evaluation/add', name: 'app_evaluation_add')]
    public function add(Request $req,ManagerRegistry $manager): Response
    {
        $evaluation = new Evaluation();
       $form = $this->createForm(EvaluationType::class,$evaluation);
       $em = $manager->getManager();
       $form->handleRequest($req);
       if($form->isSubmitted() && $form->isValid())
       {
        $em->persist($evaluation);
       $em->flush();
       return $this->redirectToRoute('app_evaluationE');
       }
        return $this->renderForm('evaluation/evaluationForm.html.twig',
         ['f' => $form]     
        );
    }
    #[Route('/yassine/{id}', name: 'affiche')]
    public function addd(EvaluationRepository $repo,OptionUserRepository $optionUserrepo,QuestionRepository $repo1,OptionRepository $repo2,ManagerRegistry $manager,$id): Response
    {
        $evaluation = $repo->findOneBy(['cours' => $id]);
        if(!$evaluation)
        {
            return $this->redirectToRoute('app_cours');
        }

        $questions = $repo1->findQuestionsByEvaluation($evaluation->getId());

        $questionOptions = [];

        foreach ($questions as $question) {
            $options = $repo2->findOptionsByQuestion($question->getId());
            $questionOptions[$question->getId()] = $options;
        }
    
        return $this->renderForm('evaluation/yassine.html.twig', [
            'questions' => $questions,
            'questionOptions' => $questionOptions,
            'optionUser' => $optionUserrepo->findByUserId(5),
        ]);
    } 
    #[Route('/yassine/{id}/evalution/{optionid}', name: 'affiche_evalution')]
    public function evalution(EvaluationRepository $repo,QuestionRepository $repo1,OptionRepository $repo2,ManagerRegistry $manager,$id,$optionid)
    {
        $optionUser = new OptionUser();
        $optionUser->setOptionId($optionid);
        $optionUser->setUserId(5);
        $em = $manager->getManager();
        $em->persist($optionUser);
        $em->flush();
        return $this->redirectToRoute('affiche',['id' => $id]);
    } 
}




