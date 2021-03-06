<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

interface ClientInterface
{
    public function get(string $path, array $params) : array ;
}