<?php

namespace App\Controller;

use App\Dto\BookListingQuery;
use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use App\Form\BookSubmissionFormType;
use App\Form\ReviewFormType;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\BookRating;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
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

    /**
     * Méthode chargée de s'occuper du système de recherche de livre dans la navbar
     */
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

    /**
     * Méthode chargée d'afficher le formulaire de soumission d'un nouveau livre
     */
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
            $this->addFlash('success', "Votre proposition de livre a bien été soumise, un administrateur se chargera de la valider.");

            return $this->redirectToRoute('app_index');
        }
        return $this->render('book/submission.html.twig', [
            'submissionForm' => $form
        ]);
    }
    /**
     * Méthode chargée d'afficher un livre seul et ses commentaires
     */
    #[Route('/{id}', name: 'book_display')]
    public function show(Book $book, BookRating $bookRating): Response
    {
        $rating = $bookRating->getRating($book);
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'rating' => $rating,
        ]);
    }
    #[Route('/{id}/review', name: 'book_reviewing')]
    public function review(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new Review;
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $review->setTimestamp(new DateTime());
            $review->setCommenter($this->getUser());
            $review->setBook($book);

            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Votre review a bien été enregistré, merci !');

            return $this->redirectToRoute('book_display', ['id'=>$book->getId()]);
        }
        return $this->render('book/review.html.twig', [
            'reviewForm' => $form,
        ]);
    }
    /**
     * Méthode qui permet de s'abonner à un livre depuis sa page.
     */
    #[Route('/{id}/subscribe', 'book_subscribe')]
    public function subscribe(#[CurrentUser] ?User $user, Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user->addSubscribedBook($book);

        $entityManager->flush();

        $this->addFlash('success', 'Vous vous êtes bien abonné à '.$book->getTitle().' !');

        return $this->redirectToRoute('book_display', ['id'=>$book->getId()]);
    }
}
