<?php

namespace Supervisorg\Workers;

use Puzzle\AMQP\Workers\Worker;
use Puzzle\AMQP\ReadableMessage;
use Supervisorg\Domain\ServerCollection;
use Psr\Log\LoggerAwareTrait;

class ProcessControl implements Worker
{
    use LoggerAwareTrait;

    private
        $servers;

    public function __construct(ServerCollection $servers)
    {
        $this->servers = $servers;
    }

    public function process(ReadableMessage $message)
    {
        $payload = $message->getDecodedBody();

        $this->ensurePayloadIsValid($payload);

        $server = $this->servers->getByName($payload['server']);
        $processName = $payload['process'];
        $action = $payload['action'];

        echo "$action $processName on " . $server->getName() . PHP_EOL;
        try
        {
            if($action === 'start')
            {
                $server->startProcess($processName);
            }
            elseif($action === 'stop')
            {
                $server->stopProcess($processName);
            }
        }
        catch(\Exception $e)
        {
            echo $e->getMessage() . PHP_EOL;

            throw $e;
        }
    }

    private function ensurePayloadIsValid(array $payload)
    {
        foreach(['server', 'process', 'action'] as $requiredEntry)
        {
            if(! isset($payload[$requiredEntry]))
            {
                throw new \InvalidArgumentException("Payload need a entry called $requiredEntry");
            }
        }
    }
}
