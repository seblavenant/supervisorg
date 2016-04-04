<?php

namespace Supervisorg\Services\XmlRPC;

use Supervisorg\Services\Process\Filter;
use Puzzle\Configuration;

class Server
{
    private
        $name,
        $filter,
        $client,
        $config;

    public function __construct($name, Client $client, Filter $filter, Configuration $config)
    {
        $this->name = (string) $name;
        $this->filter = $filter;
        $this->client = $client;
        $this->config = $config;
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
        $processList = $this->client->getProcessList();

        $processes = [];
        foreach($processList as $processInfo)
        {
            $processInfo['server'] = $this;
            $processes[] = new Process($processInfo, $this->config);
        }

        return $this->filter->filter($processes);
    }

    public function extractApplicationList()
    {
        $apps = [];

        foreach($this->getProcessList() as $process)
        {
            $apps[] = $process->getApplication();
        }

        return array_unique($apps);
    }
}
