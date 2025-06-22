<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal;

use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
use App\Module\Payment\Endpoint\Temporal\Dto\Transaction;
use App\Module\Payment\Endpoint\Temporal\Dto\TransactionStatus;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[AssignWorker(taskQueue: CreatePaymentWorkflow::TASK_QUEUE)]
#[WorkflowInterface]
final readonly class CreatePaymentWorkflow
{
    public const string TASK_QUEUE = 'payment_queue';

    /**
     * @return Transaction
     */
    #[WorkflowMethod]
    #[Workflow\ReturnType(name: Transaction::class)]
    public function pay(PaymentInfo $info)
    {
        $transactionUuid = yield Workflow::uuid7();
        $fiscalCodeUuid = yield Workflow::uuid7();

        // Simulate some processing time

        return new Transaction(
            transactionUuid: $transactionUuid,
            fiscalCodeUuid: $fiscalCodeUuid,
            paymentInfo: $info,
            status: TransactionStatus::Completed,
            createdAt: yield Workflow::now(),
        );
    }
}
