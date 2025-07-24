<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Nationality;
use App\Entity\Review;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher) 
    {
    }
    public function load(ObjectManager $manager): void
    {
        
        $generator = \Faker\Factory::create();
        $users = [];
        $regularUser = new User();
        $regularUser
            ->setEmail('peon@user.com')
            ->setName('ohheyboss')
            ->setSurname('jobsdone')
            ->setJoinDate(new DateTime('now'))
            ->setPassword( 'test');

        $manager->persist($regularUser);
        $users[] = $regularUser;

        $adminUser = new User();
        $adminUser
            ->setEmail('boss@admin.com')
            ->setName('gorgutz')
            ->setSurname('imdaboss')
            ->setJoinDate(new DateTime('now'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword( 'test');

        $manager->persist($adminUser);
        $users[] = $adminUser;

        // Ici on utilise le populator de Faker, qui auto-génère les fixtures de manière intelligente.
        // A noter qu'on est obligé de préciser les users lors de l'ajout d'entity Review, en effet le populator ne peut pas ..
        // ..assigner intelligement des valeurs qu'il n'a pas créé lui-même
        $populator = new \Faker\ORM\Doctrine\Populator($generator, $manager);
        $populator->addEntity(Nationality::class, 8);
        $populator->addEntity(Category::class, 10);
        $populator->addEntity(Author::class, 20);
        $populator->addEntity(Book::class, 30, [
            'cover' => null
        ]);
        $populator->addEntity(Review::class, 30, [
            'commenter' => $generator->randomElement($users)
        ]);
        $populator->execute();

        
        // for($i=0; $i<31; $i++)
        // {
        //     $review = new Review;
        //     $review->setAuthor($generator->randomElement($users));
        //     $review->setBook()
        // }

        $manager->flush();
    }
}
