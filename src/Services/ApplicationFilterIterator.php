<?php

namespace Supervisorg\Services;

use Supervisorg\Services\XmlRPC\Process;

class ApplicationFilterIterator extends \FilterIterator
{
    private
        $application;

    public function __construct(\Iterator $iterator, $application)
    {
        parent::__construct($iterator);

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
}
