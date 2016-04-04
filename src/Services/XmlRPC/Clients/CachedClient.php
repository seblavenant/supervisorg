<?php

namespace Supervisorg\Services\XmlRPC\Clients;

use Supervisorg\Services\XmlRPC\Client;

class CachedClient implements Client
{
    private
        $cachedList,
        $client;

    public function __construct(Client $client)
    {
        $this->cachedList = false;
        $this->client = $client;
    }

    public function getProcessList()
    {
        if($this->cachedList === false)
        {
            $this->cachedList = $this->client->getProcessList();
        }

        return $this->cachedList;
    }

    public function stopProcess($process)
    {
        return $this->client->stopProcess($process);
    }

    public function startProcess($process)
    {
        return $this->client->startProcess($process);
    }
}
