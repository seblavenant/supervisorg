<?php

namespace Supervisorg\Domain;

class ProcessCollection implements \IteratorAggregate, Collection
{
    private
        $processes;

    public function __construct(array $processes = [])
    {
        $this->processes = $processes;
    }

    public function add(ProcessCollection $collection)
    {
        foreach($collection as $process)
        {
            $this->processes[] = $process;
        }

        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->processes);
    }

    public function count()
    {
        return iterator_count($this);
    }
}
