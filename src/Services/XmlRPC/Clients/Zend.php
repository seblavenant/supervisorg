<?php

namespace Supervisorg\Services\XmlRPC\Clients;

use Supervisorg\Services\XmlRPC\Client;

class Zend implements Client
{
    private
        $client;

    public function __construct($host)
    {
        $this->client = new \Zend\XmlRpc\Client($host);
    }

    public function getProcessList()
    {
        return $this->client->call('supervisor.getAllProcessInfo');
    }

    public function startProcess($processName)
    {
        return $this->client->call('supervisor.startProcess', [$processName]);
    }

    public function stopProcess($processName)
    {
        return $this->client->call('supervisor.stopProcess', [$processName]);
    }

    public function readStdErr($process)
    {
        return $this->client->call('supervisor.readProcessStderrLog', array($process, -1000, 0));
    }
}
