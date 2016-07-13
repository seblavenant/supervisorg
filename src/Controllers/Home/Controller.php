<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Supervisorg\Services\ProcessCollectionProvider;
use Spear\Silex\Provider\Traits\TwigAware;
use Supervisorg\Domain\LogicalGroupCollection;
use Supervisorg\Services\FeatureChecker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller
{
    use
        TwigAware;

    private
        $logicalGroups,
        $processCollectionProvider,
        $featureChecker;

    public function __construct(ProcessCollectionProvider $processCollectionProvider, LogicalGroupCollection $logicalGroups, FeatureChecker $featureChecker)
    {
        $this->processCollectionProvider = $processCollectionProvider;
        $this->logicalGroups = $logicalGroups;
        $this->featureChecker = $featureChecker;
    }

    public function homeAction()
    {
        return $this->render('pages/home.twig', [
            'processes' => $this->processCollectionProvider->findAll(),
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
        ]);
    }

    public function serversAction($serverName)
    {
        if( ! $this->featureChecker->isEnabled("listByServer"))
        {
            throw new NotFoundHttpException();
        }

        return $this->render('pages\servers.twig', [
            'processes' => $this->processCollectionProvider->findByServerName($serverName),
            'currentServer' => $serverName,
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
        ]);
    }

    public function logicalGroupsAction($logicalGroupName, $logicalGroupValue)
    {
        if( ! $this->featureChecker->isEnabled("logicalGroups"))
        {
            throw new NotFoundHttpException();
        }

        return $this->render('pages\logicalGroup.twig', [
            'processes' => $this->processCollectionProvider->findByLogicalGroup($logicalGroupName, $logicalGroupValue),
            'currentLogicalGroup' => $this->logicalGroups->getByName($logicalGroupName),
            'currentLogicalGroupValue' => $logicalGroupValue,
        ]);
    }
}
