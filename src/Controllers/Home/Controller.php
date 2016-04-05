<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Services\ProcessCollectionProvider;
use Spear\Silex\Provider\Traits\TwigAware;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

        return $this->redirectToReferer();
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

        return $this->redirectToReferer();
    }

    private function redirectToReferer($defaultPath = 'home', array $defaultParameters = [])
    {
        $referer = null;

        if($this->request->headers->has('referer'))
        {
            // FIXME better to save route in sessions ?
            // quid with ajax requests ?
            $referer = $this->request->headers->get('referer');

            return new RedirectResponse($referer);
        }

        return $this->redirect($defaultPath, $defaultParameters);
    }
}
