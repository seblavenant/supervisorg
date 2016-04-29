<?php

namespace Supervisorg\Persistence\Repositories;

use MongoDB\Database;
use Supervisorg\Domain;
use Supervisorg\Persistence\UserGroupRepository;

class UserGroup implements UserGroupRepository
{
    private
        $mongo;

    public function __construct(Database $mongo)
    {
        $this->mongo = $mongo;
    }

    public function findAll()
    {
        $collection = new Domain\UserGroupCollection();

        foreach($this->fetchAll() as $record)
        {
            $collection->add($this->convertRecordToUserGroup($record));
        }

        return $collection;
    }

    public function findOne($userGroupName)
    {
        $record = $this->fetchOne($userGroupName);

        if($record === null)
        {
            return null;
        }

        return $this->convertRecordToUserGroup($record);
    }

    private function convertRecordToUserGroup($record)
    {
        return new Domain\UserGroup(
            $record->name,
            $record->label,
            $record->processes
        );
    }

    private function fetchAll()
    {
        $collection = $this->mongo->selectCollection('userGroups');

        if($collection->count() > 0)
        {
            return $collection->find([], ['typeMap' => ['array' => 'array']]);
        }

        return [];
    }

    private function fetchOne($userGroupName)
    {
        $collection = $this->mongo->selectCollection('userGroups');

        return $collection->findOne(
            ['name' => $userGroupName],
            ['typeMap' => ['array' => 'array']]
        );
    }
}
