<?php
namespace Entities\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Universe\Model\EntityCommandInterface;
use Universe\Model\Entity;

class BuildingCommand implements EntityCommandInterface
{

    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @param AdapterInterface $db
     */
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }
    
    /**
     * 
     * @truncate table
     * 
     */
    public function truncateTable()
    {
        $statement = $this->db->query('TRUNCATE TABLE buildings');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $building)
    {
        $insert = new Insert($building->GetTable());
        $insert->values([
            'name'                 => $building->getName(),
            'description'          => $building->getDescription(),
            'planet'               => $building->getPlanet()        ?   $building->getPlanet()->getId()         : null,
            'sputnik'              => $building->getSputnik()       ?   $building->getSputnik()->getId()        : null,
            'owner'                => $building->getOwner()         ?   $building->getOwner()->getId()          : null,
            'level'                => $building->getLevel(),
            'buildingType'         => $building->getBuildingType()  ?   $building->getBuildingType()->getId()   : null,
            'update'               => $building->getUpdate()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during building insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Building(
            $building->getName(),
            $building->getDescription(),
            $building->getPlanet(),
            $building->getSputnik(),
            $building->getOwner(),
            $building->getLevel(),
            $building->getBuildingType(),
            $building->getUpdate(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $building)
    {
        if (! $building->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($building->GetTable());
        $update->set([
            'name'                 => $building->getName(),
            'description'          => $building->getDescription(),
            'planet'               => $building->getPlanet()        ?   $building->getPlanet()->getId()         : null,
            'sputnik'              => $building->getSputnik()       ?   $building->getSputnik()->getId()        : null,
            'owner'                => $building->getOwner()         ?   $building->getOwner()->getId()          : null,
            'level'                => $building->getLevel(),
            'buildingType'         => $building->getBuildingType()  ?   $building->getBuildingType()->getId()   : null,
            'update'               => $building->getUpdate()
        ]);
        $update->where(['id = ?' => $building->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during building update operation'
            );
        }

        return $building;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $building)
    {
        if (! $building->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($building->getTable());
        $delete->where(['id = ?' => $building->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
