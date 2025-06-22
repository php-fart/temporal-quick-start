<?php

declare(strict_types=1);

namespace App\Module\Fiscal;

use Ramsey\Uuid\UuidInterface;

class FiscalInfo
{
    public function __construct(
        public UuidInterface $uuid,
        public int $amount,
    ) {}
}
