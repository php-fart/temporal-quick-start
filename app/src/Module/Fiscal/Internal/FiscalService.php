<?php

namespace App\Module\Fiscal\Internal;

use App\Module\Fiscal\FiscalActivity;
use App\Module\Fiscal\FiscalInfo;
use Temporal\Activity\ActivityMethod;
use Temporal\Exception\Failure\ApplicationFailure;

class FiscalService implements FiscalActivity
{
    #[ActivityMethod]
    public function fart(FiscalInfo $info): void
    {
        // Simulate some processing logic that may fail
        \mt_rand(0, 100) > 10 or throw new ApplicationFailure(
            message: 'Fatal error occurred',
            type: 'FatalError',
            nonRetryable: true,
        );

        // Simulate a service unavailability scenario
        \mt_rand(0, 100) > 50 or throw new ApplicationFailure(
            message: 'Service is unavailable',
            type: 'ServiceUnavailable',
            nonRetryable: true,
        );
    }
}
