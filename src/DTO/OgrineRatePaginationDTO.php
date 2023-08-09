<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OgrineRatePaginationDTO
{
    public function __construct(
        #[Assert\Choice(choices: ['ASC', 'DESC'])]
        public readonly string $sort = 'DESC',

        #[Assert\PositiveOrZero]
        public readonly int $limit = 0,
    ) {}
}
