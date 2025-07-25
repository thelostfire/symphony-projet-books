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

        $nationalities = [];
        for($i = 0; $i < 9; $i++)
        {
            $nationality = new Nationality();
            $nationality
                ->setName($generator->realTextBetween(5, 15));
            $manager->persist($nationality);
            $nationalities[] = $nationality;
        }

        $categories = [];
        for($i = 0; $i < 11; $i++)
        {
            $category = new Category();
            $category
                ->setName($generator->realTextBetween(4, 12));
            $manager->persist($category);
            $categories[] = $category;
        }

        $authors = [];
        for($i = 0; $i < 21; $i++)
        {
            $author = new Author();
            $author
                ->setName($generator->realTextBetween(6, 16))
                ->setSurname($generator->realTextBetween(6, 16))
                ->setNationality($generator->randomElement($nationalities))
                ->setBirthdate($generator->dateTimeThisCentury());
            $manager->persist($author);
            $authors[] = $author;
        }

        $books = [];
        for($i = 0; $i < 31; $i++)
        {
            $book = new Book();
            $book
                ->setTitle($generator->realTextBetween(8, 28))
                ->setDescription($generator->realTextBetween(40, 120))
                ->setCategory($generator->randomElement($categories))
                ->setAuthor($generator->randomElement($authors))
                ->setCover('placeholder')
                ->setIsVisible(true)
                ->setPublicationYear(random_int(0, 2025));
            $manager->persist($book);
            $books[] = $book;
        }

        $reviews = [];
        for($i = 0; $i < 31; $i++)
        {
            $review = new Review();
            $review
                ->setBook($generator->randomElement($books))
                ->setCommenter($generator->randomElement($users))
                ->setRating($generator->randomFloat(2, 0, 10))
                ->setContent($generator->realTextBetween(40, 100))
                ->setTimestamp($generator->dateTimeBetween('-2 years'));
            $manager->persist($review);
            $reviews[] = $review;
        }

        // Alors le populator c'était bien jusqu'à ce que je me rendre compte qu'essayer d'utiliser une des entités peuplées
        // dans une fixture créée en dehors du populator causait pas mal de problèmes.
        // Donc j'ai tout refait de manière classique (voir ci-dessus), le code en-dessous marche si on retire le peuplement des Book
        // (qui déconne à cause de la propriété subscribedUsers).
        //
        // $populator = new \Faker\ORM\Doctrine\Populator($generator, $manager);
        // $populator->addEntity(Nationality::class, 8);
        // $populator->addEntity(Category::class, 10);
        // $populator->addEntity(Author::class, 20);
        // $populator->addEntity(Book::class, 30);
        // $populator->addEntity(Review::class, 30, [
        //     'commenter' => $generator->randomElement($users)
        // ]);
        // $populator->execute();

        
        // for($i=0; $i<31; $i++)
        // {
        //     $review = new Review;
        //     $review->setAuthor($generator->randomElement($users));
        //     $review->setBook()
        // }

        $manager->flush();
    }
}
