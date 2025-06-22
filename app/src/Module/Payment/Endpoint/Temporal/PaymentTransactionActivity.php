<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal;

use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
use App\Module\Payment\Endpoint\Temporal\Dto\TransactionResult;
use App\Module\Payment\Endpoint\Temporal\Dto\TransactionStatus;
use App\Module\Payment\Exception\TransactionException;
use Ramsey\Uuid\UuidInterface;
use React\Promise\PromiseInterface;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AssignWorker(taskQueue: PaymentTransactionActivity::TASK_QUEUE)]
#[ActivityInterface(prefix: 'payment_transaction.')]
final readonly class PaymentTransactionActivity
{
    public const string TASK_QUEUE = 'payment_transaction_queue';

    /**
     * @return PromiseInterface<TransactionResult>
     */
    #[ActivityMethod]
    public function handle(PaymentInfo $info, UuidInterface $transactionUuid): TransactionResult
    {
        \mt_rand(0, 100) > 50 or throw new TransactionException(
            'Something went wrong during the transaction processing.',
        );

        return new TransactionResult(
            transaction: $transactionUuid,
            status: TransactionStatus::Completed,
        );
    }
}
