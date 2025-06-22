<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal\Dto;

use Ramsey\Uuid\UuidInterface;

final readonly class TransactionResult
{
    public function __construct(
        public UuidInterface $transaction,
        public TransactionStatus $status,
        public ?string $error = null,
    ) {}
}
