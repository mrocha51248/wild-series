<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Transliterator;

class Slugify
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function generate(string $input): string
    {
        return strtolower($this->slugger->slug($input));
    }
}
