<?php

namespace Supervisorg\Console;

use Puzzle\Configuration;
use Supervisorg\Application;
use Puzzle\AMQP\Commands\Worker\ListAll;
use Puzzle\AMQP\Commands\Worker\Run;
use Puzzle\Pieces\OutputInterfaceAware\NullOutputInterfaceAware;

class Worker
{
    private
        $app,
        $configuration;

    public function __construct(Application $dic)
    {
        $this->configuration = $dic['configuration'];

        $this->app = new \Symfony\Component\Console\Application('Supervisorg - workers console');

        $this->app->add(new Run($dic['amqp.client'], $dic['amqp.workerProvider'], new NullOutputInterfaceAware()));
        $this->app->add(new ListAll($dic['amqp.workerProvider']));
    }

    public function run()
    {
        $this->app->run();
    }
}