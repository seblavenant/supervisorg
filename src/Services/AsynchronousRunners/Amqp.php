<?php

namespace Supervisorg\Services\AsynchronousRunners;

use Puzzle\AMQP\Client;
use Puzzle\AMQP\WritableMessage;
use Puzzle\AMQP\Messages\Json;
use Supervisorg\Services\AsynchronousRunner;
use Supervisorg\Domain\Process;
use Supervisorg\Domain\Collection;

class Amqp implements AsynchronousRunner
{
    private
        $exchange,
        $client;

    public function __construct(Client $client, $exchange = 'supervisorg')
    {
        $this->exchange = $exchange;
        $this->client = $client;
    }

    private function send(WritableMessage $message)
    {
        $this->client->publish($this->exchange, $message);
    }

    public function start($serverName, $processName)
    {
        return $this->startStop($serverName, $processName, 'start');
    }

    public function stop($serverName, $processName)
    {
        return $this->startStop($serverName, $processName, 'stop');
    }

    public function startStop($serverName, $processName, $action)
    {
        $message = new Json('process.control.' . $action);
        $message->setBody([
            'action' => $action,
            'server' => $serverName,
            'process' => $processName,
        ]);

        $this->send($message);
    }

    public function startAll($serverName, Collection $processes)
    {
        return $this->startStopAll($serverName, $processes, 'start');
    }

    public function stopAll($serverName, Collection $processes)
    {
        return $this->startStopAll($serverName, $processes, 'stop');
    }

    public function startStopAll($serverName, Collection $processes, $action)
    {
        $names = $this->collectionToArrayOfProcessNames($processes);
        if(! empty($names))
        {
            $message = new Json('process.control.all');
            $message->setBody([
                'action' => $action,
                'server' => $serverName,
                'processes' => $names,
            ]);

            $this->send($message);
        }
    }

    private function collectionToArrayOfProcessNames(Collection $processes)
    {
        $names = [];

        foreach($processes as $process)
        {
            if($process instanceof Process)
            {
                $names[] = $process->getName();
            }
        }

        return $names;
    }
}