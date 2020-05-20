<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message/{id}", name="message")
     */
    public function message($id,Request $request, EntityManagerInterface $manager)
    {
        $message = new Message();

        if($request->getContent() != null) {
            $date = new \DateTime('@'.strtotime('now'));
            $date->add(new \DateInterval("PT2H"));
            $message->setDate($date);
            $message->setIsRead(false);
            $message->setSender($this->getUser());
            $message->setText($request->get('text'));
            $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver_id'));
            $message->setReceiver($receiver);
            $manager->persist($message);
            $manager->flush();

            return $this->render('message/message.html.twig', [
                'default_id' => $id,
            ]);
        }


        return $this->render('message/message.html.twig', [
            'default_id' => $id,
        ]);
    }
}
