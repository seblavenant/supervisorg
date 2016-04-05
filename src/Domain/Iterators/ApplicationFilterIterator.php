<?php

namespace Supervisorg\Domain\Iterators;

use Supervisorg\Domain\Process;
use Supervisorg\Domain\ProcessCollection;
use Supervisorg\Domain\Collection;

class ApplicationFilterIterator extends \FilterIterator implements Collection
{
    private
        $application;

    public function __construct(ProcessCollection $collection, $application)
    {
        parent::__construct(new \IteratorIterator($collection));

        $this->application = $application;
    }

    public function accept()
    {
        $process = $this->getInnerIterator()->current();

        if($process instanceof Process)
        {
            if($process->getApplication() === $this->application)
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
