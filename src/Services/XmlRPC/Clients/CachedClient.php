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

    public function startProcess($processName)
    {
        return $this->client->startProcess($processName);
    }

    public function stopProcess($processName)
    {
        return $this->client->stopProcess($processName);
    }

    public function startAll()
    {
        return $this->client->startAll();
    }

    public function stopAll()
    {
        return $this->client->stopAll();
    }
}
