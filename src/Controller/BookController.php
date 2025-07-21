<?php

namespace App\Controller;

use App\Dto\BookListingQuery;
use App\Entity\Book;
use App\Form\BookSubmissionFormType;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $repo): Response
    {
        return $this->render('book/index.html.twig', [
            'book_list' => $repo->findAll()
        ]);
    }

    #[Route('/search', name: 'app_book_search')]
    public function bookSearch(
        #[MapQueryString] BookListingQuery $query,
        BookRepository $repo,
        CategoryRepository $categ
    ): Response
    {
        if($query->categoryChoice === null) {
            $books = $repo->findByNameAndCategory(
            null,
            $query->search
            );
        } else {

            $books = $repo->findByNameAndCategory(
                $categ->find($query->categoryChoice),
                $query->search
            );
        }

        return $this->render('book/search.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/submit', name: 'book_submission')]
    public function submit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookSubmissionFormType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $bookPicture = $form->get('cover')->getData();
            
            $originalFile = pathinfo($bookPicture->getClientOriginalName(), PATHINFO_FILENAME);
            $slugger = new AsciiSlugger();
            $safeFile = $slugger->slug($originalFile);
            $filename = $safeFile . '-' . uniqid() . '-' . $bookPicture->guessExtension();

            try {
                $bookPicture->move(
                    'uploads/user', $filename
                );
                $book->setCover($filename);
            } catch (FileException $e) {
                $form->addError(new FormError("Erreur lors de l'upload de votre image"));
            }
            $book->setIsVisible(false);

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }
        return $this->render('book/submission.html.twig', [
            'submissionForm' => $form
        ]);
    }
    #[Route('/{id}', name: 'book_submission')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
