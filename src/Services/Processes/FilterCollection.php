<?php

namespace Supervisorg\Services\Processes;

class FilterCollection implements Filter
{
    private
        $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    public function filter(array $processList)
    {
        foreach($this->filters as $filter)
        {
            $processList = $filter->filter($processList);
        }

        return $processList;
    }
}
