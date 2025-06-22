<?php

declare(strict_types=1);

namespace App\Module\Fiscal;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface('fiscal.')]
interface FiscalActivity {
    #[ActivityMethod]
    public function fart(FiscalInfo $info): void;
}
