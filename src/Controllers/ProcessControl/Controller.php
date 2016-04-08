<?php

namespace Supervisorg\Controllers\ProcessControl;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Services\ProcessCollectionProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Controller
{
    use
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

    public function startAllAction()
    {
        $this->processCollectionProvider->startAll();

        $this->addInfoFlash('All processes are starting in background ...');

        return $this->redirectToReferer();
    }

    public function stopAllAction()
    {
        $this->processCollectionProvider->stopAll();

        $this->addInfoFlash('All processes are stopping in background ...');

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

    public function serverStartAllAction($serverName)
    {
        $this->processCollectionProvider->startAllByServerName($serverName);

        $this->addInfoFlash(sprintf(
            'Processes onto server %s are starting in background ...',
            $serverName
        ));

        return $this->redirectToReferer();
    }

    public function serverStopAllAction($serverName)
    {
        $this->processCollectionProvider->stopAllByServerName($serverName);

        $this->addInfoFlash(sprintf(
            'Processes onto server %s are stopping in background ...',
            $serverName
        ));

        return $this->redirectToReferer();
    }

    public function logicalGroupStartAllAction($logicalGroupName, $logicalGroupValue)
    {
        $this->processCollectionProvider->startAllByLogicalGroup($logicalGroupName, $logicalGroupValue);

        $this->addInfoFlash(sprintf(
            'Processes for logical group %s/%s are starting in background ...',
            $logicalGroupName,
            $logicalGroupValue
        ));

        return $this->redirectToReferer();
    }

    public function logicalGroupStopAllAction($logicalGroupName, $logicalGroupValue)
    {
        $this->processCollectionProvider->stopAllByLogicalGroup($logicalGroupName, $logicalGroupValue);

        $this->addInfoFlash(sprintf(
            'Processes for logical group %s/%s are stopping in background ...',
            $logicalGroupName,
            $logicalGroupValue
        ));

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
