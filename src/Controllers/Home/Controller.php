<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Constants\ProcessState;
use Puzzle\Configuration;
use Supervisorg\Services\ApplicationFilterIterator;

class Controller
{
    use
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\UrlGeneratorAware,
        LoggerAwareTrait;

    private
        $applications,
        $twig,
        $servers;

    public function __construct(\Twig_Environment $twig, array $servers, Configuration $config)
    {
        $this->twig = $twig;
        $this->servers = $servers;

        $this->logger = new NullLogger();

        $this->applications = $this->populateApplications($config);
    }

    private function populateApplications(Configuration $config)
    {
        if($config->read('process/applications/enabled', false))
        {
            $apps = [];

            foreach($this->servers as $server)
            {
                $apps = array_merge($apps, $server->extractApplicationList());
            }

            $apps = array_unique($apps);

            return $apps;
        }

        return false;
    }

    public function homeAction()
    {
        $processes = [];
        foreach($this->servers as $server)
        {
            $processes = array_merge($processes, $server->getProcessList());
        }

        return $this->twig->render('home.twig', [
            'servers' => $this->servers,
            'apps' => $this->applications,
            'processes' => $processes,
        ]);
    }

    public function serversAction($serverName)
    {
        $server = $this->servers[$serverName];

        return $this->twig->render('home.twig', [
            'servers' => $this->servers,
            'apps' => $this->applications,
            'processes' => $server->getProcessList(),
        ]);
    }

    public function applicationsAction($applicationName)
    {
        $processes = [];
        foreach($this->servers as $server)
        {
            $processes = array_merge($processes, $server->getProcessList());
        }

        $processes = new ApplicationFilterIterator(
            new \ArrayIterator($processes),
            $applicationName
        );

        return $this->twig->render('home.twig', [
            'servers' => $this->servers,
            'apps' => $this->applications,
            'processes' => $processes,
        ]);
    }

    public function stopProcessAction($serverName, $processName)
    {
        if(empty($processName))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        if( ! array_key_exists($serverName, $this->servers))
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $return = $this->servers[$serverName]->stopProcess($processName);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s stopping', $processName));

        return $this->redirect('home');
    }

    public function startProcessAction($serverName, $processName)
    {
        if(empty($processName))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        if( ! array_key_exists($serverName, $this->servers))
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $server = $this->servers[$serverName];
        $return = $server->startProcess($processName);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to start process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s starting', $processName));

        return $this->redirect('home');
    }
}
