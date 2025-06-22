<?php

declare(strict_types=1);

use Temporal\Worker\FeatureFlags;
use Temporal\Worker\WorkerFactoryInterface;
use Temporal\Worker\WorkerOptions;

FeatureFlags::$workflowDeferredHandlerStart = true;

/**
 * Scaffolder configuration.
 * @link https://spiral.dev/docs/basics-scaffolding
 * @see \Spiral\TemporalBridge\Config\TemporalConfig
 */
return [
    'client' => 'default',
    'defaultWorker' => WorkerFactoryInterface::DEFAULT_TASK_QUEUE,
    'workers' => [
        'default' => WorkerOptions::new()->withIdentity('My-Spiral-App'),
    ],
    'interceptors' => [],
];
