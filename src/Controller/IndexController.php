<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Service\BookRating;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(BookRepository $bookRepo, BookRating $bookRating): Response
    {
        $books = $bookRepo->getLastSixBooks();
        /**
         * Ici j'ai pété un câble et ai fait un truc probablement horrible
         * Le but est de récupérer toutes les ratings des $books et de les lier à des clés définies par
         * l'id du book auquel elles correspondent. Est-ce que ça marche ? Oui. Est-ce que ça aurait été plus simple d'en faire 
         * une variable globale / autre dans config.services? probablement.
         * Mais j'ai passé une heure à faire cette chose donc j'ai bien l'intention qu'il en reste un trace. Pour la postérité.
         */
        $ratings = [];
        foreach($books as $book)
        {
            $ratings[$book->getId()] = $bookRating->getRating($book);
        }
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'books' => $books,
            'ratings' => $ratings,
        ]);
    }
}
