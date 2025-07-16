<?php

namespace App\EventListener;

use App\Entity\Book;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\PostPersist;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEntityListener(event: Events::postPersist, method: 'messageAdmin', entity: Book::class)]
class MessageAdminOnBookSubmission
{
    public function __construct(private MailerInterface $mailer) 
    {  
    }
    public function messageAdmin(Book $book, PostPersistEventArgs $args, UserRepository $repo)
    {
        // $email = (new Email())
        //     ->from('autosend@bookstory.fr')
        //     foreach()
        //     ->to($user->getEmail())
        //     ->subject('Inscription à un site de livres ! ( ͡° ͜ʖ ͡°)')
        //     ->text('Nous confirmons que vous vous êtes bien inscrit à notre site de livres')
        //     ->html('<p>Nous confirmons que vous vous êtes bien inscrit à notre site de livres</p>');

        //     $this->mailer->send($email);

        //TODO : faire un foreach qui récupère les admins, pour ça faire une méthode dans le repo users, chercher comment récupérer les
        //       admins (en lien avec le tableau de rôles qui est en json)
    }
}