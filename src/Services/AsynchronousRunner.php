<?php

namespace Supervisorg\Services;

use Supervisorg\Domain\Collection;

interface AsynchronousRunner
{
    public function startAll($serverName, Collection $processes);
}
