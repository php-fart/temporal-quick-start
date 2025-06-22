<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal\Dto;

use Ramsey\Uuid\UuidInterface;

final readonly class Transaction implements \JsonSerializable
{
    public function __construct(
        public UuidInterface $transactionUuid,
        public UuidInterface $fiscalCodeUuid,
        public PaymentInfo $paymentInfo,
        public TransactionStatus $status,
        public \DateTimeImmutable $createdAt,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'transaction_uuid' => $this->transactionUuid->toString(),
            'fiscal_code_uuid' => $this->fiscalCodeUuid->toString(),
            'payment_info' => $this->paymentInfo,
            'status' => $this->status->name,
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
        ];
    }
}
