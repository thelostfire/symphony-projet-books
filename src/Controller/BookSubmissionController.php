<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookSubmissionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookSubmissionController extends AbstractController
{
    #[Route('/submit', name: 'book_submission')]
    public function submit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookSubmissionFormType::class, $book);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid())
        {
            $book->setIsVisible(false);

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }
        return $this->redirectToRoute('book_submit');
    }
}