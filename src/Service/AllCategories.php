<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class AllCategories {
    public function __construct(private CategoryRepository $repo) 
    {
    }

    /**
     * @return Category
     */
    public function getCategories():array
    {
        return $this->repo->findAll();
    }
}