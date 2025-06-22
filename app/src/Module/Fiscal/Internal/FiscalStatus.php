<?php

declare(strict_types=1);

namespace App\Module\Fiscal\Internal;

use Ramsey\Uuid\UuidInterface;

final readonly class FiscalStatus
{
    public function __construct(
        public UuidInterface $uuid,
        public string $status,
        public ?string $error = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
