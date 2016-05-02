<?php

namespace Supervisorg\Controllers\UserGroups;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Domain\ServerCollection;
use Spear\Silex\Provider\Traits\TwigAware;
use Supervisorg\Persistence\UserGroupRepository;
use Supervisorg\Domain\UserGroup;

class Controller
{
    use
        TwigAware,
        LoggerAwareTrait,
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\UrlGeneratorAware;

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

    public function editAction($userGroupName)
    {
        $userGroup = $this->userGroupRepository->findOne($userGroupName);
        
        if(! $userGroup instanceof UserGroup)
        {
            $this->addErrorFlash("User group ($userGroupName) not found");
            
            return $this->redirect('configure_userGroups');
        }
        
        return $this->render('pages/configure/editUserGroups.twig', [
            'servers' => $this->servers,
            'userGroup' => $userGroup,
        ]);
    }

    public function deleteAction($userGroupName)
    {
        $success = $this->userGroupRepository->delete($userGroupName);
        
        $this->addResultFlash($success, "User group ($userGroupName) has been successfully deleted", "Error while deleting user group ($userGroupName)");
        
        return $this->redirect('configure_userGroups');
    }
}
