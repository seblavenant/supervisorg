<?php

namespace Supervisorg\Console;

use Puzzle\Configuration;
use Spear\Silex\Provider\Commands\AsseticDumper;
use Supervisorg\Application;

class Package
{
    private
        $app,
        $configuration;

    public function __construct(Application $dic)
    {
        $this->configuration = $dic['configuration'];

        $this->app = new \Symfony\Component\Console\Application('Supervisorg - packaging console');

        $this->app->add(new AsseticDumper($this->configuration, $dic['assetic.dumper'], $dic['assetic.path_to_web']));
    }

    public function run()
    {
        $this->app->run();
    }
}