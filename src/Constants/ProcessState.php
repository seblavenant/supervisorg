<?php

namespace Supermonitord\Constants;

interface ProcessState
{
    const
        STOPPED = 'STOPPED',
        STARTING = 'STARTING',
        RUNNING = 'RUNNING',
        BACKOFF = 'BACKOFF',
        STOPPING = 'STOPPING',
        EXITED = 'EXITED',
        FATAL = 'FATAL',
        UNKNOWN = 'UNKNOWN';
}
