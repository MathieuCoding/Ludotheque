<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(GameRepository $repo): Response
    {
        $games = $repo->findAll();
        //dump($games);
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games
        ]);
    }

    #[Route('/game/new', name: 'app_add_game')]
    public function add(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $em = $doctrine->getManager();
        $newgame = new Game;
        $form = $this->createForm(GameType::class, $newgame)
            ->add('enregistrer', SubmitType::class)
            ->add('effacer', ResetType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pictureFile = $form->get('picture')->getData();

            // this condition is needed becauspicture' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                // Move the file to the directorypictures are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updatepictureFilename' property to store the PDF file name
                // instead of its contents
                $newgame->setPicture($newFilename);
            
            }
            $newgame = $form->getData();
            $em->persist($newgame);
            $em->flush();
        }

        return $this->render('game/add.html.twig', [
            'form' =>$form
        ]);
    }

    #[Route('/game/{id}', name: 'app_show_game')]
    public function show(GameRepository $repo, $id): Response
    {
        $game = $repo->find($id);
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);

    }

}
