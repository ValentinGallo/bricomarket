<?php

namespace App\Controller;

use App\Entity\Department;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();

        return $this->render('accueil/index.html.twig', [
            'list_department' => $departments,
        ]);
    }
}
