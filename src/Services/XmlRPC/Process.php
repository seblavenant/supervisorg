<?php

namespace Supervisorg\Services\XmlRPC;

class Process
{
    private
        $name,
        $description,
        $server,
        $statename;

    public function __construct(array $info)
    {
        $this->name = $info['name'];
        $this->description = $info['description'];
        $this->server = $info['server'];
        $this->statename = $info['statename'];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getStatename()
    {
        return $this->statename;
    }
}
