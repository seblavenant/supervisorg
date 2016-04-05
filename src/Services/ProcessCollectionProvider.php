<?php

namespace Supervisorg\Services;

use Supervisorg\Domain\ServerCollection;
use Supervisorg\Domain\ProcessCollection;
use Supervisorg\Domain\Iterators\ApplicationFilterIterator;
use Supervisorg\Domain\Collection;

class ProcessCollectionProvider
{
    private
        $servers;

    public function __construct(ServerCollection $servers)
    {
        $this->servers = $servers;
    }

    /**
     * @return Collection
     */
    public function findAll()
    {
        $collection = new ProcessCollection();

        foreach($this->servers as $server)
        {
            $collection->add($server->getProcessList());
        }

        return $collection;
    }

    /**
     * @return Collection
     */
    public function findByServerName($serverName)
    {
        $server = $this->servers->getByName($serverName);

        return $server->getProcessList();
    }

    /**
     * @return Collection
     */
    public function findByApplicationName($applicationName)
    {
        return new ApplicationFilterIterator(
            $this->findAll(),
            $applicationName
        );
    }

    public function startProcess($serverName, $processName)
    {
        if(empty($processName))
        {
            throw new \RuntimeException("Process name must be valued");
        }

        $server = $this->servers->getByName($serverName);
        $return = $server->startProcess($processName);

        if(! $return)
        {
            throw new \RuntimeException("Error while trying to start process $processName onto server $serverName");
        }
    }

    public function stopProcess($serverName, $processName)
    {
        if(empty($processName))
        {
            throw new \RuntimeException("Process name must be valued");
        }

        $server = $this->servers->getByName($serverName);
        $return = $server->stopProcess($processName);

        if(! $return)
        {
            throw new \RuntimeException("Error while trying to stop process $processName onto server $serverName");
        }
    }
}
