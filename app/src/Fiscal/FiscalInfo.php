<?php

declare(strict_types=1);

namespace App\Fiscal;

use Ramsey\Uuid\UuidInterface;

class FiscalInfo
{
    public function __construct(
        public UuidInterface $uuid,
        public int $amount,
    ) {}
}
