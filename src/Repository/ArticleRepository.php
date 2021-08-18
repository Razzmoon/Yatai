<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function limitArticleHomePage(){
        $queryBuilder = $this->createQueryBuilder('article');

        //affiche seulement 3 top article
        $query = $queryBuilder
            ->select('article')
            ->setMaxResults(3)
            ->getQuery();
        //transforme la requete sql php en requete sql
        return $query->getResult();
    }

    //fonction pour rechercher par rapport a la catégorie//tag//article
    public function search($term)
    {
        //commande sql en php
        $queryBuilder = $this->createQueryBuilder('article');

        $query = $queryBuilder
            ->select('article')

            ->leftJoin('article.category', 'category')
            ->orWhere('article.title LIKE :term')
            ->orWhere('category.title LIKE :term')

            ->setParameter('term', '%'.$term.'%')
            ->getQuery();
        //transforme la requete sql php en requete sql
        return $query->getResult();
    }
}
