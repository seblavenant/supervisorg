<?php

namespace Supervisorg\Services\XmlRPC;

interface Client
{
    public function getProcessList();

    public function startProcess($processName);

    public function stopProcess($processName);

    public function startAll();

    public function stopAll();
}
