<?php

namespace Supermonitord\Services\Process\Filters;

use Supermonitord\Services\Process\Filter;

// FIXLE : add unit tests
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
            if( ! is_array($process))
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
        if(in_array($process['name'], $this->filteredProcessNames))
        {
            return true;
        }

        return false;
    }
}
