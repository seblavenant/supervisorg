<?php

namespace Supervisorg\Domain\Iterators;

use Supervisorg\Domain\Process;
use Supervisorg\Domain\ProcessCollection;
use Supervisorg\Domain\Collection;
use Supervisorg\Domain\LogicalGroup;

class LogicalGroupFilterIterator extends \FilterIterator implements Collection
{
    private
        $logicalGroup,
        $logicalGroupValue;

    public function __construct(ProcessCollection $collection, LogicalGroup $logicalGroup, $logicalGroupValue)
    {
        parent::__construct(new \IteratorIterator($collection));

        $this->logicalGroup = $logicalGroup;
        $this->logicalGroupValue = $logicalGroupValue;
    }

    public function accept()
    {
        $process = $this->getInnerIterator()->current();

        if($process instanceof Process)
        {
            if($this->logicalGroup->belongTo($process, $this->logicalGroupValue))
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
