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

    public function add($mixed)
    {
        if($mixed instanceof ProcessCollection)
        {
            return $this->addCollection($mixed);
        }

        if($mixed instanceof Process)
        {
            return $this->addProcess($mixed);
        }

        return $this;
    }

    private function addCollection(ProcessCollection $collection)
    {
        foreach($collection as $process)
        {
            $this->addProcess($process);
        }
    }

    private function addProcess(Process $process)
    {
        $this->processes[] = $process;
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
