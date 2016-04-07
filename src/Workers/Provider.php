<?php

namespace Supervisorg\Workers;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Puzzle\AMQP\Workers\WorkerContext;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['worker.processControl'] = $app->share(function($c) {
            $context = new WorkerContext(
                function () use ($c) {
                    return new ProcessControl($c['supervisor.servers']);
                },
                $c['amqp.consumers.simple'],
                $c['configuration']->read('amqp/supervisorg/queues/processControl', 'process_control')
            );

            return $context;
        });

        $app['worker.processControlAll'] = $app->share(function($c) {
            $context = new WorkerContext(
                function () use ($c) {
                    return new ProcessControlAll($c['asynchronous.runner']);
                },
                $c['amqp.consumers.simple'],
                $c['configuration']->read('amqp/supervisorg/queues/processControlAll', 'process_control_all')
            );

            return $context;
        });
    }

    public function boot(Application $app)
    {
    }
}
