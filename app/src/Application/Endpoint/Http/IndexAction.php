<?php

declare(strict_types=1);

namespace App\Application\Endpoint\Http;

use App\Module\Payment\Endpoint\Temporal\CreatePaymentWorkflow;
use App\Module\Payment\Endpoint\Temporal\Dto\PaymentInfo;
use Carbon\CarbonInterval;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

final readonly class IndexAction
{
    public function __construct(
        private ViewsInterface $views,
        private InputManager $request,
        private WorkflowClientInterface $wf,
    ) {}

    #[Route(
        route: '/',
        methods: ['GET'],
        group: 'web',
    )]
    public function index(): string
    {
        return $this->views->render('index');
    }

    #[Route(
        route: '/create-payment',
        methods: ['POST'],
        group: 'web',
    )]
    public function createPayment(): string
    {
        try {
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

            return $this->views->render('index', [
                'result' => $result,
                'success' => true,
                'amount' => $amount,
                'description' => $description
            ]);
        } catch (\Exception $e) {
            return $this->views->render('index', [
                'error' => 'Payment creation failed: ' . $e->getMessage(),
                'success' => false
            ]);
        }
    }
}
