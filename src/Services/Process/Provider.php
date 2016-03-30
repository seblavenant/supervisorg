<?php

namespace Supervisorg\Services\Process;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['process.filter.process-name'] = $app->protect(function($filteredProcessNames) {
            return new Filters\Name($filteredProcessNames);
        });
    }

    public function boot(Application $app)
    {
    }
}
