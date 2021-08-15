<?php

namespace App\Controller\Front;


use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route ("/categories", name="categories")
     */

    public function category_list(CategoryRepository $categoryRepository,UserRepository $userRepository)
    {
        $user = $userRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('front/category.html.twig',[
            'category' =>$categories,
            'user' => $user
        ]);
    }
    /**
     * @Route ("/categorie/{id}", name="categorie_show")
     */

    public function categorie_show($id, CategoryRepository $categoryRepository)
    {
        $categorie = $categoryRepository->find($id);
        if (is_null($categorie)){
            throw new NotFoundHttpException();
        }
        return $this->render("front/restauration.html.twig", [
            'categorie' => $categorie
        ]);
    }
}