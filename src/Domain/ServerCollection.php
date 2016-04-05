<?php

namespace Supervisorg\Domain;

class ServerCollection implements \IteratorAggregate, Collection
{
    private
        $servers;

    public function __construct()
    {
        $this->servers = array();
    }

    public function add(Server $server)
    {
        $this->servers[$server->getName()] = $server;

        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->servers);
    }

    /**
     * @return Server
     */
    public function getByName($serverName)
    {
        if(isset($this->servers[$serverName]))
        {
            return $this->servers[$serverName];
        }

        throw new \RuntimeException("Server $serverName not found");
    }

    public function count()
    {
        return iterator_count($this);
    }
}
