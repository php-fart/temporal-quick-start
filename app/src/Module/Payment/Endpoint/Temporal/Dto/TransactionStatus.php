<?php

declare(strict_types=1);

namespace App\Module\Payment\Endpoint\Temporal\Dto;

enum TransactionStatus
{
    case Pending;
    case Completed;
    case Failed;
}
