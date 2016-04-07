<?php

namespace Supervisorg\Console;

use Puzzle\Configuration;
use Supervisorg\Application;

class Console
{
    private
        $app,
        $configuration;

    public function __construct(Application $dic)
    {
        $this->configuration = $dic['configuration'];

        $this->app = new \Symfony\Component\Console\Application('Supervisorg - command console');

        $this->app->add(new Commands\GreetCommand());
    }

    public function run()
    {
        $this->app->run();
    }
}