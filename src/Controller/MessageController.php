<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function message(Request $request, EntityManagerInterface $manager)
    {
        //$users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $message->setDate(new \DateTime('@'.strtotime('now')));
            $message->setIsRead(false);
            $message->setSender($this->getUser());

            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('message/message.html.twig', ['form' => $form->createView()]);
    }
}
