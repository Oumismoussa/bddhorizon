<?php


namespace App\Controller;

use App\Entity\Quizz;
use App\Form\QuizzType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuizzController extends AbstractController
{
    /**
    * @Route("/quizz/new", name="quizz_new")
    * @param Request $request
    * @return Response 
    */

    public function new(Request $request): Response
    {
        $quizz = new Quizz(); 
        $form = $this ->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($quizz);
            $em->flush();
            
        }
        return $this->render('quizz/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

}