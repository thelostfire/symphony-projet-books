<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Retourne une liste de livres qui incluent la catégorie sélectionnée et dont le nom contient le string $title
     * @param \App\Entity\Category $category
     * @param string $name
     * @return Book[]
     */
    public function findByNameAndCategory(?Category $category = null, string $title): array
    {
        if($category === null) {
            $books = $this->createQueryBuilder('b')
            ->andWhere('b.title like :title')
            ->setParameter('title', '%'.$title.'%')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();

        return $books;
        }

        $books = $this->createQueryBuilder('b')
            ->andWhere('b.category = :category')
            ->andWhere('b.title like :title')
            ->setParameter('category', $category)
            ->setParameter('title', '%'.$title.'%')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();

        return $books;
    }

    public function getLastSixBooks(): array
    {
        $books = $this->createQueryBuilder('b')
            ->select('b')
            ->andWhere('b.isVisible = 1')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();

        return $books;
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
