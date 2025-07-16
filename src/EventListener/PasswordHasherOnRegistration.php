<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'hashPassword', entity: User::class)]
// #[AsEntityListener(event: Events::postPersist, method: 'hashPassword', entity: User::class)] //Exemple d'un postPersist sur même classe, à écrire en dessous 
class PasswordHasherOnRegistration
{
    public function __construct(private UserPasswordHasherInterface $hasher) {
        
    }
    public function hashPassword(User $user, PrePersistEventArgs $args):void {
        $plainPasswd = $user->getPassword();
        $user->setPassword($this->hasher->hashPassword($user, $plainPasswd));
    }
}