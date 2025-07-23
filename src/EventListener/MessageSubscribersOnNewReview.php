<?php

namespace App\EventListener;

use App\Entity\Review;
use App\Repository\BookRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEntityListener(Events::postPersist, 'messageSubscribers', entity: Review::class)]
/**
 * Classe chargée d'envoyer un message aux subscribers d'un livre quand ce dernier reçoit un nouveau review
 */
class MessageSubscribersOnNewReview
{
    public function __construct(private MailerInterface $mailer, BookRepository $repo) 
    {    
    }

    public function messageSubscribers(Review $review, PostPersistEventArgs $args)
    {
        $review = $args->getObject();
        if(!$review instanceof Review)
        {
            return;
        }

        $book = $review->getBook();
        $subscribers = $book->getSubscribedUsers();
        foreach($subscribers as $subscriber)
        {
            $email = (new Email())
                ->from('autosend@bookstory.fr')
                ->to($subscriber->getEmail())
                ->subject('Un livre que vous suivez a reçu un nouveau review')
                ->text("Le livre".$book->getTitle()." a reçu un review de l'utilisateur ".$subscriber->getName())
                ->html("<p>Le livre".$book->getTitle()." a reçu un review de l'utilisateur ".$subscriber->getName()."</p>");

            $this->mailer->send($email);
        }
    }
}