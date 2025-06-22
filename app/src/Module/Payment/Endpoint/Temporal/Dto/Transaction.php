<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal\Dto;

use App\Module\Fiscal\Internal\FiscalStatus;

final readonly class Transaction implements \JsonSerializable
{
    public function __construct(
        public PaymentInfo $paymentInfo,
        public ?TransactionResult $transactionResult,
        public ?FiscalStatus $fiscalCode,
        public \DateTimeImmutable $createdAt,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'transaction' => $this->transactionResult ? [
                'uuid' => $this->transactionResult->transaction->toString(),
                'status' => $this->transactionResult->status->name,
                'error' => $this->transactionResult->error,
            ] : null,
            'fiscal_code' => $this->fiscalCode ? [
                'uuid' => $this->fiscalCode->uuid->toString(),
                'status' => $this->fiscalCode->status,
                'error' => $this->fiscalCode->error,
                'created_at' => $this->fiscalCode->createdAt?->format(\DateTimeInterface::ATOM),
                'updated_at' => $this->fiscalCode->updatedAt?->format(\DateTimeInterface::ATOM),
            ] : null,
            'payment_info' => $this->paymentInfo,
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
        ];
    }
}
