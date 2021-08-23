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

    //fonction pour rechercher par rapport a la catégorie//article//
    //term represente la chaine de caractere rempli par l'utilisateur
    public function search($term)
    {
        //je fais appel a mon entité article
        $queryBuilder = $this->createQueryBuilder('article');

        //je met ma variable queryBuilder dans une variable query
        $query = $queryBuilder
            //je fais appel a mon entité article
            ->select('article')
            //je fais une liaison avec category
            ->leftJoin('article.category', 'category')
            //je fais le trie par titre d'article
            ->orWhere('article.title LIKE :term')
            //je fais le trie par titre de category
            ->orWhere('category.title LIKE :term')

            //protection contre les injection SQL
            ->setParameter('term', '%'.$term.'%')
            //getquery() fais la requete
            ->getQuery();
        //return $query renvoi le resultat de la requete
        return $query->getResult();
    }
}
