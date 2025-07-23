<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;

class BookRating {
public function __construct(private float $i = 0, private float $rating = 0) 
{
}

    /**
     * Retourne la moyenne des notes d'un livre
     * @param \App\Entity\Book $book
     * @return string
     */
    public function getRating(Book $book): string
    {
        $reviews = $book->getReviews();
        $this->i = 0;
        $this->rating = 0;
        foreach($reviews as $review)
        {
            $this->rating = $this->rating + floatval($review->getRating());
            $this->i ++;
        }
        
        if($this->i != 0) {
            
            $this->rating = $this->rating / $this->i;
            return number_format($this->rating, 2, '.', ' ');
        }
            return "n/a";
    }
}