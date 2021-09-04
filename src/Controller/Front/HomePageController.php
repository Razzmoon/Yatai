<?php

namespace App\Controller\Front;


use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ArticleRepository $articleRepository,UserRepository $userRepository)
    {
        $user = $userRepository->findAll();
        //j'utilise la fonction limite article que je vien de crée
        $articles = $articleRepository->limitArticleHomePage();
        return $this->render('Front/home.html.twig', [
            'articles' => $articles,
            'user' => $user
        ]);
    }
    /**
     * @Route("/InfoContact", name="InfoContact")
     */
    public function infocontact(UserRepository $userRepository)
    {
        $user= $userRepository->findAll();
        return $this->render('Front/InfoContact.html.twig', [
            'user'=> $user

        ]);
    }
    /**
     * @Route("/NotreVision", name="notreVision")
     */
    public function vision(UserRepository $userRepository)
    {
        $user = $userRepository->findAll();
        return $this->render('Front/Vision.html.twig', [
            'user' => $user

        ]);
    }
}
