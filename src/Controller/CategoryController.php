<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    
    #[Route('/category/new', name: 'app_add_category')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $newcat = new Category;
        $form = $this->createForm(CategoryType::class, $newcat)
            ->add('enregistrer', SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $newcat = $form->getData();
            // Enregistre l'objet
            $em->persist($newcat);
            // Envoie l'objet
            $em->flush();
        }
        return $this->render('category/add.html.twig', [
            'form' => $form
        ]);
    }

      #[Route('/category/{id}', name: 'app_show_category')]
    public function show(CategoryRepository $repo, $id): Response
    {
        $category = $repo->find($id);
        dump($category);
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);

    }

}
