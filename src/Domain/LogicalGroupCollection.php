<?php

namespace Supervisorg\Domain;

class LogicalGroupCollection implements \IteratorAggregate, Collection
{
    private
        $logicalGroups;

    public function __construct()
    {
        $this->logicalGroups = array();
    }

    public function add(LogicalGroup $logicalGroup)
    {
        $this->logicalGroups[$logicalGroup->getName()] = $logicalGroup;

        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->logicalGroups);
    }

    /**
     * @return LogicalGroup
     */
    public function getByName($logicalGroupName)
    {
        if(isset($this->logicalGroups[$logicalGroupName]))
        {
            return $this->logicalGroups[$logicalGroupName];
        }

        throw new \RuntimeException("Logical group $logicalGroupName not found");
    }

    public function count()
    {
        return iterator_count($this);
    }
}
