<?php

namespace App\Controller;
use App\Entity\Quizz;
use \Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
  /**
   * @Route("/test")
   */
    public function index()
    {
        $em = $this->getDoctrine()->getManager(); 
        $quizz = new Quizz();
        $quizz->setTheme(theme:'theme1');
        $quizz->setTitle(title:"titre1");
        $em->persist($quizz);
        $em->flush();
        return $this->render('main/index.html.twig');
    }
}
