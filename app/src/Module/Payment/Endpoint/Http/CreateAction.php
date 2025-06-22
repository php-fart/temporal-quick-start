<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Http;

use App\Module\Payment\Endpoint\Temporal\CreatePaymentWorkflow;
use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
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
        $amount = (int) $this->request->data('amount', 1000);
        $description = $this->request->data('description', 'No description provided');

        // Validate input

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

        // Response format

        return $result;
    }
}
