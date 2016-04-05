<?php

namespace Supervisorg\Services\XmlRPC;

interface Client
{
    public function getProcessList();

    public function startProcess($process);

    public function stopProcess($process);

    public function startAll();

    public function stopAll();
}
