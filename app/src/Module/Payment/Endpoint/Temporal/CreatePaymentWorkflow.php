<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal;

use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
use App\Module\Payment\Endpoint\Temporal\Dto\Transaction;
use Carbon\CarbonInterval;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Exception\Failure\ActivityFailure;
use Temporal\Exception\Failure\CanceledFailure;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[AssignWorker(taskQueue: CreatePaymentWorkflow::TASK_QUEUE)]
#[WorkflowInterface]
final readonly class CreatePaymentWorkflow
{
    const string TASK_QUEUE = 'payment_queue';

    private ActivityProxy|PaymentTransactionActivity $pay;

    public function __construct()
    {
        $this->pay = Workflow::newActivityStub(
            PaymentTransactionActivity::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::minute())
                ->withRetryOptions(
                    RetryOptions::new()
                        ->withMaximumAttempts(1),
                )
                ->withTaskQueue(PaymentTransactionActivity::TASK_QUEUE),
        );
    }

    /**
     * @return Transaction
     */
    #[WorkflowMethod(name: 'payment.create')]
    #[Workflow\ReturnType(name: Transaction::class)]
    public function pay(PaymentInfo $info)
    {
        $transactionUuid = yield Workflow::uuid7();
        $fiscalCodeUuid = yield Workflow::uuid7();

        try {
            $transactionResult = yield $this->pay->handle($info, $transactionUuid);
        } catch (ActivityFailure $e) {
            trap($e);

            throw new CanceledFailure(
                'Payment transaction failed',
                $e->getCode(),
                $e,
            );
        }

        // Simulate some processing time

        return new Transaction(
            paymentInfo: $info,
            transactionResult: $transactionResult,
            fiscalCodeUuid: $fiscalCodeUuid,
            createdAt: yield Workflow::now(),
        );
    }
}
