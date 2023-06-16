<?php

declare(strict_types=1);

namespace Clearbooks\LabsApi\Cors;

class AllowedOrigins
{
    private array $allowedOrigins;

    public function __construct($allowedOrigins = [])
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    public function getAllowedOrigins(): array
    {
        return $this->allowedOrigins;
    }
}
