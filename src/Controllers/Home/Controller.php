<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Services\ProcessCollectionProvider;
use Spear\Silex\Provider\Traits\TwigAware;

class Controller
{
    use
        TwigAware,
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\UrlGeneratorAware,
        LoggerAwareTrait;

    private
        $processCollectionProvider;

    public function __construct(ProcessCollectionProvider $processCollectionProvider)
    {
        $this->processCollectionProvider = $processCollectionProvider;
        $this->logger = new NullLogger();
    }

    public function homeAction()
    {
        return $this->render('home.twig', [
            'title' => 'All processes',
            'processes' => $this->processCollectionProvider->findAll(),
        ]);
    }

    public function serversAction($serverName)
    {
        return $this->render('home.twig', [
            'title' => $serverName,
            'processes' => $this->processCollectionProvider->findByServerName($serverName),
        ]);
    }

    public function applicationsAction($applicationName)
    {
        return $this->render('home.twig', [
            'title' => "$applicationName",
            'processes' => $this->processCollectionProvider->findByApplicationName($applicationName),
        ]);
    }

    public function stopProcessAction($serverName, $processName)
    {
        try
        {
            $this->processCollectionProvider->stopProcess($serverName, $processName);
            $this->addInfoFlash(sprintf('Process <b>%s</b> stopping on server <b>%s</b>', $processName, $serverName));
        }
        catch(\Exception $e)
        {
            $this->addErrorFlash($e->getMessage());
        }

        return $this->redirect('home');
    }

    public function startProcessAction($serverName, $processName)
    {
        try
        {
            $this->processCollectionProvider->startProcess($serverName, $processName);
            $this->addInfoFlash(sprintf('Process <b>%s</b> starting on server <b>%s</b>', $processName, $serverName));
        }
        catch(\Exception $e)
        {
            $this->addErrorFlash($e->getMessage());
        }

        return $this->redirect('home');
    }
}
