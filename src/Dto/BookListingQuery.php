<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BookListingQuery {
    public function __construct(
        
        public readonly ?int $categoryChoice = null,
        
        #[Assert\NotBlank]
        #[Assert\Length(max: 60)]
        public readonly string $search
    ) {
    }
}