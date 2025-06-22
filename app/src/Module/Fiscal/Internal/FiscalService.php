<?php

namespace App\Module\Fiscal\Internal;

use App\Module\Fiscal\FiscalActivity;
use App\Module\Fiscal\FiscalInfo;
use Temporal\Activity\ActivityMethod;
use Temporal\Exception\Failure\ApplicationFailure;

class FiscalService implements FiscalActivity
{
    #[ActivityMethod]
    public function fart(FiscalInfo $info): FiscalStatus
    {
        try {
            // Simulate some processing logic that may fail
            \mt_rand(0, 100) > 10 or throw new ApplicationFailure(
                message: 'Fatal error occurred',
                type: 'FatalError',
                nonRetryable: true,
            );
        } catch (\Throwable $e) {
            // Handle the exception and return a failure status
            return new FiscalStatus(
                uuid: $info->uuid,
                status: 'failure',
                error: $e->getMessage(),
                createdAt: new \DateTimeImmutable(),
                updatedAt: new \DateTimeImmutable(),
            );
        }

        try {
            // Simulate a service unavailability scenario
            \mt_rand(0, 100) > 50 or throw new ApplicationFailure(
                message: 'Service is unavailable',
                type: 'ServiceUnavailable',
                nonRetryable: true,
            );
        } catch (ApplicationFailure $e) {
            // Handle the service unavailability and return a failure status
            return new FiscalStatus(
                uuid: $info->uuid,
                status: 'service_unavailable',
                error: $e->getMessage(),
                createdAt: new \DateTimeImmutable(),
                updatedAt: new \DateTimeImmutable(),
            );
        }

        return new FiscalStatus(
            uuid: $info->uuid,
            status: 'success',
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
        );
    }
}
