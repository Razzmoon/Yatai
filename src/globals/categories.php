<?php


namespace App\globals;


use App\Repository\CategoryRepository;

class categories
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll()
    {
        $categorie = $this->categoryRepository->findAll();

        return $categorie;
    }

}
