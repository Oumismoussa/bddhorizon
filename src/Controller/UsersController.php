<?php


namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UsersController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
       /**
     * @var Mailer
     */
    private $mailer;
        /**
     * @var UserRepository
     */
    private $userRepository;



    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer, UsersRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;

    }


     /**
     * @Route("/users/new", name="register")
     * @param Request $request
     * @return Response
     */

    public function register( Request $request): Response
    {
        $users = new Users(); 
        $form = $this->createForm(UsersType::class, $users);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $users->setPassword(
                $this->passwordEncoder->encodePassword($users, $form->get("password")->getData())
            );
            $users->setToken($this->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($users);
            $em->flush();
            $this->mailer->sendEmail($users->getEmail(), $users->getToken());
            $this->addFlash("success", "Inscription réussie !");
        }
        
        return $this->render('users/users.html.twig', [
            "form" => $form->createView()
        ]);
    }

   


    
    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token)
    {
        $user = $this->userRepository->findOneBy(["token" => $token]);
        if($user) {
            $user->setToken(null);
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Compte actif !");
            return $this->redirectToRoute("home");
        } else {
            $this->addFlash("error", "Ce compte n'exsite pas !");
            return $this->redirectToRoute('home');  
        }
    }
    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}



