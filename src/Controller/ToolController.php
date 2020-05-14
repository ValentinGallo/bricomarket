<?php

namespace App\Controller;

use App\Entity\Tool;
use App\Form\ToolType;
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
    public function listTool(EntityManagerInterface $manager)
    {
        $tools = $manager->getRepository(Tool::class)->findAll();
        return $this->render('tool/list.html.twig', [
            'list_tools' => $tools,
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

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

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
            $tool->setDate(new \DateTime('@'.strtotime('now')));
            $tool->setCreator($this->getUser());
            $manager->persist($tool);
            $manager->flush();

            return $this->redirectToRoute('list_tool');
        }

        return $this->render('tool/createTool.html.twig', ['form' => $form->createView()]);
    }
}
