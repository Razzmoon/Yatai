<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddArticleController extends AbstractController
{

    /**
     * @Route("/admin/articles", name="admin_article_List")
     */
    public function articleList(ArticleRepository $articleRepository,UserRepository $userRepository,CategoryRepository $categoryRepository)
    {
        $articles = $articleRepository->findAll();
        $user = $userRepository->findAll();
        $category = $categoryRepository->findAll();


        return $this->render('admin/Admin_Articles_List.html.twig', [
            'articles' => $articles,
            'user' => $user,
            'categories' => $category

        ]);
    }

    /**
     * @Route("admin/insert/article", name="article_insert")
     */
    public function insertArticle(Request $request,ArticleRepository $articleRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $articles = $articleRepository->findAll();

        $article = new Article();

        //On genere le formulaire en utilisant le gabarit + une instance de l'entité article
        $articleForm = $this->createForm(ArticleType::class, $article);

        //on lie le formulaire au donné de POST (donné envoyer par post)
        $articleForm->handleRequest($request);

        //si le form a été poster et qu'il et valide (que tous les champ obligatoire son bien rempli)
        //alors on enregistre l'article en bdd=
        if ($articleForm->isSubmitted()&&$articleForm->isValid()){

            $brochureFile = $articleForm->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $article->setBrochureFilename($brochureFileName);
            }

            // permet de stocker en session un message flash, dans le but de l'afficher
            // sur la page suivante
            $this->addFlash(
                'success',
                'L\'Article '. $article->getTitle().' a bien été créé !'
            );

            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('article_insert');

        }

        return $this->render('Admin/AdminAddArticle.html.twig', [
            'articleForm' => $articleForm->createView(),
            'articles' => $articles


        ]);
    }

    /**
     * @Route("admin/search", name="search")
     */
    public function search(ArticleRepository $articleRepository, Request $request)
    {
        $term = $request->query->get('q');

        $articles = $articleRepository->search($term);

        return $this->render('Admin/Adminarticle_search.html.twig', [
            'articles' => $articles,
            'term' => $term
        ]);
    }

    /**
     * @Route("admin/articles/update/{id}", name="admin_article_Update")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request,  FileUploader $fileUploader)
    {

        // pour l'insert : $article = new Article();
        $article = $articleRepository->find($id);

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Article
        $articleForm = $this->createForm(ArticleType::class, $article);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $articleForm->handleRequest($request);

        // si le formulaire a été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre l'article
        // créé en bdd
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            $brochureFile = $articleForm->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $article->setBrochureFilename($brochureFileName);
            }
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_article_List');
        }


        return $this->render('Admin/AdminAddArticle.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }

    /**
     * @Route("/article/delete/{id}",name="admin_article_Delete")
     */
    public function deleteArticle($id,ArticleRepository $articleRepository,EntityManagerInterface $entityManager)
    {
        $article= $articleRepository->find($id);
        $entityManager->remove($article);
        //prend tous et direction la bdd
        $entityManager->flush();

        //redirige vers la page article_list
        return $this->redirectToRoute('admin_article_List');
    }
}