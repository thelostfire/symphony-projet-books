<?php

namespace App\Controller;

use App\Dto\BookListingQuery;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class BookSearchController extends AbstractController
{
    #[Route('/search', name: 'app_book_search')]
    public function bookSearch(
        #[MapQueryString] BookListingQuery $query,
        BookRepository $repo,
        CategoryRepository $categ
    ): Response
    {
        $books = $repo->findByNameAndCategory(
            $categ->find($query->categoryChoice),
            $query->search
        );

        return $this->render('book_search/index.html.twig', [
            'books' => $books,
        ]);
    }
}
