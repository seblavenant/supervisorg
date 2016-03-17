<?php

namespace Supervisorg\Services\XmlRPC;

interface Client
{
    public function getProcessList();

    public function stopProcess($process);

    public function startProcess($process);
}
