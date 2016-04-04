<?php

namespace Supervisorg\Services\Process\Filters;

use Supervisorg\Services\Process\Filter;
use Supervisorg\Services\XmlRPC\Process;

// FIXME : add unit tests
class Name implements Filter
{
    private
        $filteredProcessNames;

    public function __construct(array $filteredProcessNames)
    {
        $this->filteredProcessNames = $filteredProcessNames;
    }

    public function filter(array $processList)
    {
        foreach($processList as $index => $process)
        {
            if( ! $process instanceof Process)
            {
                continue;
            }

            if($this->isFiltered($process))
            {
                unset($processList[$index]);
            }
        }

        return $processList;
    }

    private function isFiltered(array $process)
    {
        if(in_array($process->getName(), $this->filteredProcessNames))
        {
            return true;
        }

        return false;
    }
}
