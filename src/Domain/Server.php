<?php

namespace Supervisorg\Domain;

use Supervisorg\Services\Processes\Filter;
use Supervisorg\Services\XmlRPC\Client;

class Server
{
    private
        $name,
        $filter,
        $client;

    public function __construct($name, Client $client, Filter $filter)
    {
        $this->name = (string) $name;
        $this->filter = $filter;
        $this->client = $client;
    }

    public function getName()
    {
        return $this->name;
    }

    public function startProcess($processName)
    {
        return $this->client->startProcess($processName);
    }

    public function stopProcess($processName)
    {
        return $this->client->stopProcess($processName);
    }

    /**
     * @return \Supervisorg\Domain\ProcessCollection
     */
    public function getProcessList()
    {
        $processList = $this->client->getProcessList();

        $processes = [];
        foreach($processList as $processInfo)
        {
            $processInfo['server'] = $this;
            $processes[] = new Process($processInfo);
        }

        $processes = $this->filter->filter($processes);

        return new ProcessCollection($processes);
    }

    public function extractLogicalGroupValues(LogicalGroup $logicalGroup)
    {
        $values = [];

        foreach($this->getProcessList() as $process)
        {
            if($logicalGroup->belongToAny($process))
            {
                $values[] = $logicalGroup->getValue($process);
            }
        }

        return array_unique($values);
    }
}
