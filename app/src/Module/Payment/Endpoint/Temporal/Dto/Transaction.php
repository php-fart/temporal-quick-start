<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal\Dto;

use Ramsey\Uuid\UuidInterface;

final readonly class Transaction implements \JsonSerializable
{
    public function __construct(
        public PaymentInfo $paymentInfo,
        public TransactionResult $transactionResult,
        public UuidInterface $fiscalCodeUuid,
        public \DateTimeImmutable $createdAt,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'transaction' => [
                'uuid' => $this->transactionResult->transaction->toString(),
                'status' => $this->transactionResult->status->name,
                'error' => $this->transactionResult->error,
            ],
            'fiscal_code_uuid' => $this->fiscalCodeUuid->toString(),
            'payment_info' => $this->paymentInfo,
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
        ];
    }
}
