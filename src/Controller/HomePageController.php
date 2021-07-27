<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        // je retourne une réponse HTTP valide en utilisant
        // la classe Response du composant HTTPFoundation
        return $this->render('home.html.twig', [
            'articles' => $articles
        ]);
    }
}
