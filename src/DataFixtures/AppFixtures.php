<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Nationality;
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

        // USERS

        $regularUser = new User();
        $regularUser
            ->setEmail('peon@user.com')
            ->setName('ohheyboss')
            ->setSurname('jobsdone')
            ->setJoinDate(new DateTime('now'))
            ->setPassword($this->hasher->hashPassword($regularUser, 'test'));

        $manager->persist($regularUser);

        $adminUser = new User();
        $adminUser
            ->setEmail('boss@admin.com')
            ->setName('gorgutz')
            ->setSurname('imdaboss')
            ->setJoinDate(new DateTime('now'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($adminUser, 'test'));

        $manager->persist($adminUser);

        // OTHERS

        $generator = \Faker\Factory::create();
        $populator = new \Faker\ORM\Doctrine\Populator($generator, $manager);
        $populator->addEntity(Nationality::class, 8);
        $populator->addEntity(Category::class, 10);
        $populator->addEntity(Author::class, 20);
        $populator->addEntity(Book::class, 30);
        $populator->execute();

        $manager->flush();
    }
}
