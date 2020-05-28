<?php

namespace App\Controller;

use App\Entity\Category;
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

        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('accueil/index.html.twig', [
            'list_department' => $departments,
            'list_category' => $category,
        ]);
    }

        /**
     * @Route("/contrat", name="contrat")
     */
    public function contrat()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        
        return $this->render('accueil/contrat.html.twig', [
            'list_category' => $category,
        ]);
    }
}
