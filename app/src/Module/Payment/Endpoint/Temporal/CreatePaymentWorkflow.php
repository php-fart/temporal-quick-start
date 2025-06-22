<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal;

use App\Module\Fiscal\FiscalActivity;
use App\Module\Fiscal\FiscalInfo;
use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
use App\Module\Payment\Endpoint\Temporal\Dto\Transaction;
use App\Module\Payment\Endpoint\Temporal\Dto\TransactionResult;
use Carbon\CarbonInterval;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\DataConverter\EncodedValues;
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
    public const string TASK_QUEUE = 'payment_queue';

    private ActivityProxy|FiscalActivity $fiscalCode;

    public function __construct()
    {
        $this->fiscalCode = Workflow::newActivityStub(
            FiscalActivity::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::minute())
                ->withRetryOptions(
                    RetryOptions::new()
                        ->withMaximumAttempts(5),
                )
                ->withTaskQueue(FiscalActivity::TASK_QUEUE),
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

        try {
            # Start the payment transaction activity
            $transactionResult = yield Workflow::executeActivity(
                'payment_transaction.handle',
                [$info, $transactionUuid],
                options: ActivityOptions::new()
                    ->withStartToCloseTimeout(CarbonInterval::minute())
                    ->withRetryOptions(
                        RetryOptions::new()
                            ->withMaximumAttempts(10),
                    )
                    ->withTaskQueue(PaymentTransactionActivity::TASK_QUEUE)
                    ->withSummary('Processing payment transaction ' . $transactionUuid->toString()),
                returnType: TransactionResult::class,
            );
        } catch (ActivityFailure $e) {
            throw new CanceledFailure(
                'Payment transaction was canceled due to an error',
                EncodedValues::fromValues([
                    'result' => new Transaction(
                        paymentInfo: $info,
                        transactionResult: null,
                        fiscalCode: null,
                        createdAt: yield Workflow::now(),
                    ),
                ]),
            );
        }

        $fiscalCodeUuid = yield Workflow::uuid7();

        $fiscalCodeStatus = yield $this->fiscalCode->fart(
            new FiscalInfo(
                uuid: $fiscalCodeUuid,
                transactionUuid: $transactionUuid,
                amount: $info->amount,
            ),
        );

        return new Transaction(
            paymentInfo: $info,
            transactionResult: $transactionResult,
            fiscalCode: $fiscalCodeStatus,
            createdAt: yield Workflow::now(),
        );
    }
}
