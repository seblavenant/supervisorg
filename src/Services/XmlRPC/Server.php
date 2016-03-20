<?php

namespace Supervisorg\Services\XmlRPC;

class Server
{
    private
        $name,
        $client;

    public function __construct($name, Client $client)
    {
        $this->name = (string) $name;
        $this->client = $client;
    }

    public function getName()
    {
        return $this->name;
    }

    public function stopProcess($process)
    {
        return $this->client->stopProcess($process);
    }

    public function startProcess($process)
    {
        return $this->client->startProcess($process);
    }

    public function getProcessList()
    {
        return $this->client->getProcessList();
    }
}
