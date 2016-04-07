<?php

namespace Supervisorg\Services;

use Supervisorg\Domain\Collection;

interface AsynchronousRunner
{
    public function start($serverName, $processName);
    public function stop($serverName, $processName);
    public function startStop($serverName, $processName, $action);

    public function startAll($serverName, Collection $processes);
    public function stopAll($serverName, Collection $processes);
    public function startStopAll($serverName, Collection $processes, $action);
}
