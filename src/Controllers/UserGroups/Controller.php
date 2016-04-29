<?php

namespace Supervisorg\Controllers\UserGroups;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Domain\ServerCollection;
use Spear\Silex\Provider\Traits\TwigAware;
use Supervisorg\Persistence\UserGroupRepository;

class Controller
{
    use
        TwigAware,
        Traits\RequestAware,
        LoggerAwareTrait;

    private
        $servers,
        $userGroupRepository,
        $logicalGroups;

    public function __construct(ServerCollection $servers, UserGroupRepository $userGroupRepository)
    {
        $this->servers = $servers;
        $this->userGroupRepository = $userGroupRepository;

        $this->logger = new NullLogger();
    }

    public function homeAction()
    {
        return $this->render('pages/configure/userGroups.twig', [
            'servers' => $this->servers,
            'userGroups' => $this->userGroupRepository->findAll(),
        ]);
    }
}
