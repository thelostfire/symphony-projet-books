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
/**
 * Classe chargée d'envoyer un mail aux admins dès qu'un nouveau livre est soumis par un utilisateur
 */
class MessageAdminOnBookSubmission
{
    public function __construct(private MailerInterface $mailer, private UserRepository $repo) 
    {  
    }
    public function messageAdmin(Book $book, PostPersistEventArgs $args)
    {
        $admins = $this->repo->findAllAdmins();
        $book = $args->getObject();
        if(!$book instanceof Book) {
            return;
        }
        foreach($admins as $admin) {
            
            $email = (new Email())
                ->from('autosend@bookstory.fr')
                ->to($admin->getEmail())
                ->subject('Nouvelle soumission de livre')
                ->text('Un utilisateur a proposé un nouveau livre :'.$book->getTitle())
                ->html('<p>Un utilisateur a proposé un nouveau livre: '.$book->getTitle().'</p>');

            $this->mailer->send($email);
        }
    }
} 