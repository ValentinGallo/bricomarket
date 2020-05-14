<?php

namespace App\Controller;

use App\Entity\Tool;
use App\Form\ToolType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToolController extends AbstractController
{
    /**
     * @Route("/tool", name="tool")
     */
    public function index()
    {
        return $this->render('tool/index.html.twig', [
            'controller_name' => 'ToolController',
        ]);
    }

    /**
     * @Route("/creation_annonce", name="create_tool")
     */
    public function createTool(Request $request, EntityManagerInterface $manager)
    {
        $tool = new Tool();

        $form = $this->createForm(ToolType::class, $tool);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $tool->setDate(new \DateTime('@'.strtotime('now')));

            $manager->persist($tool);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('tool/index.html.twig', ['form' => $form->createView()]);
    }
}
