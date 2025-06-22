<?php

declare(strict_types=1);

namespace App\Module\Fiscal;

use App\Module\Fiscal\Internal\FiscalStatus;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AssignWorker(taskQueue: FiscalActivity::TASK_QUEUE)]
#[ActivityInterface('fiscal.')]
interface FiscalActivity
{
    public const string TASK_QUEUE = 'fiscal_queue';

    #[ActivityMethod]
    public function fart(FiscalInfo $info): FiscalStatus;
}
