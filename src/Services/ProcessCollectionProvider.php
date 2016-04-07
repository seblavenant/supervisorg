<?php

namespace Supervisorg\Services;

use Supervisorg\Domain\ServerCollection;
use Supervisorg\Domain\ProcessCollection;
use Supervisorg\Domain\Iterators\ApplicationFilterIterator;
use Supervisorg\Domain\Collection;

class ProcessCollectionProvider
{
    private
        $servers,
        $runner;

    public function __construct(ServerCollection $servers, AsynchronousRunner $runner)
    {
        $this->servers = $servers;
        $this->runner = $runner;
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

    public function startAllByServerName($serverName)
    {
        $this->runner->startAll(
            $serverName,
            $this->findByServerName($serverName)
        );
    }

    public function stopAllByServerName($serverName)
    {
        $this->runner->stopAll(
            $serverName,
            $this->findByServerName($serverName)
        );
    }

    public function startAll()
    {
        return $this->startAllOntoDifferentServers(
            $this->findAll()
        );
    }

    public function stopAll()
    {
        return $this->stopAllOntoDifferentServers(
            $this->findAll()
        );
    }

    public function startAllByApplicationName($applicationName)
    {
        return $this->startAllOntoDifferentServers(
            $this->findByApplicationName($applicationName)
        );
    }

    public function stopAllByApplicationName($applicationName)
    {
        return $this->stopAllOntoDifferentServers(
            $this->findByApplicationName($applicationName)
        );
    }

    private function startAllOntoDifferentServers(Collection $processes)
    {
        $processesByServer = $this->groupProcessesByServer($processes);

        foreach($processesByServer as $serverName => $processes)
        {
            $this->runner->startAll($serverName, $processes);
        }
    }

    private function stopAllOntoDifferentServers(Collection $processes)
    {
        $processesByServer = $this->groupProcessesByServer($processes);

        foreach($processesByServer as $serverName => $processes)
        {
            $this->runner->stopAll($serverName, $processes);
        }
    }

    private function groupProcessesByServer(Collection $processes)
    {
        $processesByServer = [];

        foreach($processes as $process)
        {
            $server = $process->getServer();
            if(! isset($processesByServer[$server->getName()]))
            {
                $processesByServer[$server->getName()] = new ProcessCollection();
            }

            $processesByServer[$server->getName()]->add($process);
        }

        return $processesByServer;
    }
}
