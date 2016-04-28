<?php

namespace Supervisorg\Domain\Iterators;

use Supervisorg\Domain\Process;
use Supervisorg\Domain\ProcessCollection;
use Supervisorg\Domain\Collection;
use Supervisorg\Domain\LogicalGroup;

class NameFilterIterator extends \FilterIterator implements Collection
{
    private
        $names;

    public function __construct(ProcessCollection $collection, array $names)
    {
        parent::__construct(new \IteratorIterator($collection));

        $this->names = $names;
    }

    public function accept()
    {
        $process = $this->getInnerIterator()->current();

        if($process instanceof Process)
        {
            if(in_array($process->getName(), $this->names))
            {
                return true;
            }
        }

        return false;
    }

    public function count()
    {
        return iterator_count($this);
    }
}
