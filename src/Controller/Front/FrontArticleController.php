<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontArticleController extends AbstractController
{
    /**
     * @Route("/articles/{id}", name="article_Show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        // afficher un article en fonction de l'id renseigné dans l'url (en wildcard)
        $article = $articleRepository->find($id);

        // si l'article n'a pas été trouvé, je renvoie une exception (erreur)
        // pour afficher une 404
        if (is_null($article)) {
            throw new NotFoundHttpException();
        }

        return $this->render('Front/articleShow.html.twig', [
            'article' => $article
        ]);
    }
}