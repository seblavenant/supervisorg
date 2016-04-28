<?php

namespace Supervisorg\Services;

use Supervisorg\Domain\ServerCollection;
use Supervisorg\Domain\ProcessCollection;
use Supervisorg\Domain\Collection;
use Supervisorg\Domain\Iterators\LogicalGroupFilterIterator;
use Supervisorg\Domain\LogicalGroupCollection;
use MongoDB\Database;
use Supervisorg\Domain\Iterators\NameFilterIterator;

class ProcessCollectionProvider
{
    private
        $servers,
        $logicalGroups,
        $mongo,
        $runner;

    public function __construct(ServerCollection $servers, LogicalGroupCollection $logicalGroups, AsynchronousRunner $runner, Database $mongo)
    {
        $this->servers = $servers;
        $this->logicalGroups = $logicalGroups;
        $this->runner = $runner;
        $this->mongo = $mongo;
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
    public function findByLogicalGroup($logicalGroupName, $logicalGroupValue)
    {
        return new LogicalGroupFilterIterator(
            $this->findAll(),
            $this->logicalGroups->getByName($logicalGroupName),
            $logicalGroupValue
        );
    }

    /**
     * @return Collection
     */
    public function findByUserGroup($userGroupName)
    {
        $collection = $this->mongo->selectCollection('userGroups');

        $group = $collection->findOne(
            ['name' => $userGroupName],
            ['typeMap' => ['array' => 'array']]
        );

        return new NameFilterIterator(
            $this->findAll(),
            $group->processes
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

    public function startAllByLogicalGroup($logicalGroupName, $logicalGroupValue)
    {
        return $this->startAllOntoDifferentServers(
            $this->findByLogicalGroup($logicalGroupName, $logicalGroupValue)
        );
    }

    public function stopAllByLogicalGroup($logicalGroupName, $logicalGroupValue)
    {
        return $this->stopAllOntoDifferentServers(
            $this->findByLogicalGroup($logicalGroupName, $logicalGroupValue)
        );
    }

    public function startAllByUserGroup($userGroupName)
    {
        return $this->startAllOntoDifferentServers(
            $this->findByUserGroup($userGroupName)
        );
    }

    public function stopAllByUserGroup($userGroupName)
    {
        return $this->stopAllOntoDifferentServers(
            $this->findByUserGroup($userGroupName)
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
