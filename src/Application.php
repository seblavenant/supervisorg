<?php

namespace Supervisorg;

use Spear\Silex\Application\AbstractApplication;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Spear\Silex\Provider;
use Puzzle\Configuration;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Puzzle\AMQP\Silex\AmqpServiceProvider;

class Application extends AbstractApplication
{
    protected function registerProviders()
    {
        $this->register(new SessionServiceProvider());

        $this->register(new Provider\Twig());
        $this->register(new HttpFragmentServiceProvider());
        $this->register(new Provider\AsseticServiceProvider());
        $this->register(new Persistence\Provider());
        $this->register(new Services\Provider());
        $this->register(new Workers\Provider());
        $this->register(new \Spear\AdminLTE\Provider());
        $this->register(new AmqpServiceProvider());
    }

    protected function initializeServices()
    {
        $this->configureTwig();
    }

    private function configureTwig()
    {
        $this['twig.path.manager']->addPath(array(
            $this['root.path'] . 'views/',
        ));
    }

    protected function mountControllerProviders()
    {
        $this->mount('/', new Controllers\Home\Provider());
        $this->mount('/', new Controllers\ProcessControl\Provider());
        $this->mount('/ui', new Controllers\UI\Provider());
        $this->mount('/configure/user-groups', new Controllers\UserGroups\Provider());
    }
}
