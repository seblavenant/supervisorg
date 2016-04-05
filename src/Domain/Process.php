<?php

namespace Supervisorg\Domain;

use Puzzle\Configuration;

class Process
{
    private
        $name,
        $description,
        $server,
        $statename,
        $config;

    public function __construct(array $info, Configuration $config)
    {
        $this->name = $info['name'];
        $this->description = $info['description'];
        $this->server = $info['server'];
        $this->statename = $info['statename'];
        $this->config = $config;
    }

    public function getName()
    {
        $name = $this->interpretFullName();

        if(isset($name['process']))
        {
            return $name['process'];
        }

        return $this->getFullName();
    }

    public function getFullName()
    {
        return $this->name;
    }

    public function getApplication()
    {
        $name = $this->interpretFullName();

        if(isset($name['application']))
        {
            return $name['application'];
        }

        return $name;
    }

    private function interpretFullName()
    {
        if($this->config->read('process/applications/enabled', false))
        {
            $regex = $this->config->readRequired('process/applications/regex');

            if(preg_match("~$regex~", $this->name, $matches))
            {
                $application = $matches[1];
                if(isset($matches['application']))
                {
                    $application = $matches['application'];
                }

                $processName = $matches[2];
                if(isset($matches['process']))
                {
                    $processName = $matches['process'];
                }

                return [
                    'application' => $application,
                    'process' => $processName
                ];
            }
        }

        return null;
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
