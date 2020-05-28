<?php

namespace App\Controller;


use App\Entity\Tool;
use App\Entity\User;
use App\Form\ToolType;
use App\Entity\Message;
use App\Entity\Category;
use App\Form\MessageType;
use App\Entity\Department;
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
            $manager->persist($tool);
            $manager->flush();

            return $this->redirectToRoute('list_tool');
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
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime('@'.strtotime('now'));
            $date->add(new \DateInterval("PT2H"));
            $message->setDate($date);
            $message->setIsRead(false);
            $message->setSender($this->getUser());

            $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver_id'));
            $message->setReceiver($receiver);
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('message');
        }

        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($id);
        return $this->render('tool/tool.html.twig', [
            'tool' => $tool,
            'form' => $form->createView(),
            'list_category' => $category,
        ]);
    }
}
