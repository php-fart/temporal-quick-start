<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Http;

use App\Module\Payment\Endpoint\Temporal\CreatePaymentWorkflow;
use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
use App\Module\Payment\Endpoint\Temporal\Dto\Transaction;
use Carbon\CarbonInterval;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Annotation\Route;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

final readonly class CreateAction
{
    public function __construct(
        private InputManager $request,
        private WorkflowClientInterface $wf,
    ) {}

    #[Route(
        route: 'payment/create',
        methods: ['POST'],
        group: 'api',
    )]
    public function __invoke(): \JsonSerializable
    {
        $amount = $this->request->data('amount');
        $description = $this->request->data('description', 'No description provided');

        $wf = $this->wf->newWorkflowStub(
            CreatePaymentWorkflow::class,
            WorkflowOptions::new()
                ->withTaskQueue(CreatePaymentWorkflow::TASK_QUEUE)
                ->withWorkflowExecutionTimeout(CarbonInterval::minutes(5)),
        );

        // async
        $run = $this->wf->start(
            $wf,
            new PaymentInfo(
                amount: $amount,
                description: $description,
            ),
        );

        $result = $run->getResult();

        return $result;
    }
}
