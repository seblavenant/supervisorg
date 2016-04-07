<?php

namespace Supervisorg\Workers;

use Puzzle\AMQP\Workers\Worker;
use Puzzle\AMQP\ReadableMessage;
use Supervisorg\Services\AsynchronousRunner;
use Psr\Log\LoggerAwareTrait;

class ProcessControlAll implements Worker
{
    use LoggerAwareTrait;

    private
        $runner;

    public function __construct(AsynchronousRunner $runner)
    {
        $this->runner = $runner;
    }

    public function process(ReadableMessage $message)
    {
        $payload = $message->getDecodedBody();

        $this->ensurePayloadIsValid($payload);

        $serverName = $payload['server'];
        $action = $payload['action'];

        foreach($payload['processes'] as $processName)
        {
            $this->runner->startStop($serverName, $processName, $action);
        }
    }

    private function ensurePayloadIsValid(array $payload)
    {
        foreach(['server', 'processes', 'action'] as $requiredEntry)
        {
            if(! isset($payload[$requiredEntry]))
            {
                throw new \InvalidArgumentException("Payload need a entry called $requiredEntry");
            }
        }
    }
}
