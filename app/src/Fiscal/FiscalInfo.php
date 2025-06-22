<?php

declare(strict_types=1);

namespace App\Fiscal;

use Temporal\Activity\ActivityMethod;
use Temporal\Exception\Failure\ApplicationFailure;

class FiscalInfo implements FiscalActivity
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
