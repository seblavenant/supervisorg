<?php

namespace Supervisorg\Persistence\Repositories;

use MongoDB\Database;
use Supervisorg\Domain;
use Supervisorg\Persistence\UserGroupRepository;

class UserGroup implements UserGroupRepository
{
    const
        COLLECTION_NAME = 'userGroups';
    
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
        $collection = $this->mongo->selectCollection(self::COLLECTION_NAME);

        if($collection->count() > 0)
        {
            return $collection->find([], ['typeMap' => ['array' => 'array']]);
        }

        return [];
    }

    private function fetchOne($userGroupName)
    {
        $collection = $this->mongo->selectCollection(self::COLLECTION_NAME);

        return $collection->findOne(
            ['name' => $userGroupName],
            ['typeMap' => ['array' => 'array']]
        );
    }
    
    public function delete($userGroupName)
    {
        $collection = $this->mongo->selectCollection(self::COLLECTION_NAME);
        
        $deleteResult = $collection->deleteOne([
            'name' => $userGroupName,
        ]);
        
        return $deleteResult->getDeletedCount() > 0;
    }
}
