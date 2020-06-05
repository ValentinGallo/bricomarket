<?php

namespace App\Controller;


use App\Entity\Tool;
use App\Entity\User;
use App\Form\ToolType;
use App\Entity\Message;
use App\Entity\Category;
use App\Entity\Location;
use App\Form\MessageType;
use App\Entity\Department;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ToolController extends AbstractController
{
    /**
     * @Route("/annonces", name="list_tool")
     */
    public function listTool(Request $request)
    {
        
        $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();

        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $tools = $this->getDoctrine()->getRepository(Tool::class)->findCustom($request->get('name'),$request->get('department'),$request->get('category'));

        return $this->render('tool/list.html.twig', [
            'list_department' => $departments,
            'list_category' => $category,
            'list_tools' => $tools,
            'name' => $request->get('name'),
            'dep' => $request->get('department'),
            'cat' => $request->get('category'),
        ]);
    }

    /**
     * @Route("/creation_annonce", name="create_tool")
     */
    public function createTool(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger)
    {
        $tool = new Tool();

        $form = $this->createForm(ToolType::class, $tool);

        $form->handleRequest($request);

        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $tool->setPicture($newFilename);
            }
            $tool->setDate(new \DateTime('@' . strtotime('now')));
            $tool->setCreator($this->getUser());
            $tool->setIsOnline(true);
            $manager->persist($tool);
            $manager->flush();

            return $this->redirectToRoute('tool',['id'=>  $tool->getId()]);
        }

        return $this->render('tool/createTool.html.twig', [
            'form' => $form->createView(),
            'list_category' => $category,
            ]);
    }

    /**
     * @Route("/outil/{id}", name="tool")
     */
    public function tool($id,Request $request,EntityManagerInterface $manager)
    {
        //Outil
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($id);

        //Message form
        $message = new Message();

        $formMessage = $this->createForm(MessageType::class, $message);

        $formMessage->handleRequest($request);

        //Location form
        $location = new Location();

        $formLocation = $this->createForm(LocationType::class, $location);

        $formLocation->handleRequest($request);

        //Liste des catégories (navbar)
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if($formMessage->isSubmitted() && $formMessage->isValid()) {
            $date = new \DateTime('@'.strtotime('now'));
            $date->add(new \DateInterval("PT2H"));
            $message->setDate($date);
            $message->setIsRead(false);
            $message->setSender($this->getUser());

            $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver_id'));
            $message->setReceiver($receiver);
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('message',['id'=> $request->get('receiver_id')]);
        }

        if($formLocation->isSubmitted() && $formLocation->isValid()) {
            $date = new \DateTime('@'.strtotime('now'));
            $date->add(new \DateInterval("PT2H"));
            $location->setCreateTime($date);
            //$location->setIsAccept(null);
            $location->setUser($this->getUser());
            $location->setTool($tool);

            $manager->persist($location);
            $manager->flush();

            return $this->redirectToRoute('tool',['id'=> $id]);
        }

        
        return $this->render('tool/tool.html.twig', [
            'tool' => $tool,
            'form_message' => $formMessage->createView(),
            'form_location' => $formLocation->createView(),
            'list_category' => $category,
        ]);
    }

    /**
     * @Route("/update_online/{id}/{value}", name="update_online")
     */
    public function update_online($id,$value,EntityManagerInterface $manager)
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($id);
        $tool->setIsOnline($value);
        $manager->persist($tool);
        $manager->flush();
        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/update_accept/{id}/{value}", name="update_accept")
     */
    public function update_accept($id,$value,EntityManagerInterface $manager)
    {
        $location = $this->getDoctrine()->getRepository(Location::class)->find($id);
        $location->setIsAccept($value);
        $manager->persist($location);
        $manager->flush();
        return $this->redirectToRoute('profil');
    }

}
