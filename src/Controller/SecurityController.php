<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /** 
     * @route("/registration", name="app_registration")
    */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setCreateTime(new \DateTime('@'.strtotime('now')));
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'list_category' => $category,
            ]);
    }

    /** 
     * @route("/login", name="app_login")
    */
    public function login(){
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('security/login.html.twig',[
            'list_category' => $category,
        ]);
    }

    /** 
     * @route("/logout", name="app_logout")
    */
    public function logout(){
    }
    
}
