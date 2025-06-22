<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal\Dto;

final readonly class PaymentInfo
{
    public function __construct(
        public int $amount,
        public string $description,
    ) {}
}
