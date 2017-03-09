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

class SpaceSheepCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE spacesheeps');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $spaceSheep)
    {
        $insert = new Insert($spaceSheep->GetTable());
        $insert->values([
            'name'                          => $spaceSheep->getName(),
            'description'                   => $spaceSheep->getDescription(),
            'speed'                         => $spaceSheep->getSpeed(), 
            'capacity'                      => $spaceSheep->getCapacity(), 
            'fuel_consumption'              => $spaceSheep->getFuelConsumption(), 
            'fuel_tank_size'                => $spaceSheep->getFuelTankSize(), 
            'attak_power'                   => $spaceSheep->getAttakPower(), 
            'rate_of_fire'                  => $spaceSheep->getRateOfFire(), 
            'the_number_of_attak_targets'   => $spaceSheep->getTheNumberOfAttakTarget(),
            'sheep_size'                    => $spaceSheep->getSheepSize(),
            'protection'                    => $spaceSheep->getProtection(),
            'number_of_guns'                => $spaceSheep->getNumberOfGuns(),
            'construction_time'             => $spaceSheep->getConstructionTime(),
            'fuel_rest'                     => $spaceSheep->getFuelRest(),
            'galaxy'                        => $spaceSheep->getGalaxy()         ? $spaceSheep->getGalaxy()->getId()         : null,
            'planetSystem'                  => $spaceSheep->getPlanetSystem()   ? $spaceSheep->getPlanetSystem()->getId()   : null,
            'star'                          => $spaceSheep->getStar()           ? $spaceSheep->getStar()->getId()           : null,
            'planet'                        => $spaceSheep->getPlanet()         ? $spaceSheep->getPlanet()->getId()         : null,
            'sputnik'                       => $spaceSheep->getSputnik()        ? $spaceSheep->getSputnik()->getId()        : null,
            'owner'                         => $spaceSheep->getOwner()          ? $spaceSheep->getOwner()->getId()          : null,
            'event'                         => $spaceSheep->getEvent()          ? $spaceSheep->getEvent()->getId()          : null
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during SpaceSheep insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new SpaceSheep(
            $spaceSheep->getName(),
            $spaceSheep->getDescription(),
            $spaceSheep->getSpeed(), 
            $spaceSheep->getCapacity(), 
            $spaceSheep->getFuelConsumption(), 
            $spaceSheep->getFuelTankSize(), 
            $spaceSheep->getAttakPower(), 
            $spaceSheep->getRateOfFire(), 
            $spaceSheep->getTheNumberOfAttakTarget(),
            $spaceSheep->getSheepSize(),
            $spaceSheep->getProtection(),
            $spaceSheep->getNumberOfGuns(),
            $spaceSheep->getConstructionTime(),
            $spaceSheep->getFuelRest(),
            $spaceSheep->getGalaxy(),
            $spaceSheep->getPlanetSystem(),
            $spaceSheep->getStar(),
            $spaceSheep->getPlanet(),
            $spaceSheep->getSputnik(),
            $spaceSheep->getOwner(),
            $spaceSheep->getEvent(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $spaceSheep)
    {
        if (! $spaceSheep->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($spaceSheep->GetTable());
        $update->set([
            'name'                          => $spaceSheep->getName(),
            'description'                   => $spaceSheep->getDescription(),
            'speed'                         => $spaceSheep->getSpeed(), 
            'capacity'                      => $spaceSheep->getCapacity(), 
            'fuel_consumption'              => $spaceSheep->getFuelConsumption(), 
            'fuel_tank_size'                => $spaceSheep->getFuelTankSize(), 
            'attak_power'                   => $spaceSheep->getAttakPower(), 
            'rate_of_fire'                  => $spaceSheep->getRateOfFire(), 
            'the_number_of_attak_targets'   => $spaceSheep->getTheNumberOfAttakTarget(),
            'sheep_size'                    => $spaceSheep->getSheepSize(),
            'protection'                    => $spaceSheep->getProtection(),
            'number_of_guns'                => $spaceSheep->getNumberOfGuns(),
            'construction_time'             => $spaceSheep->getConstructionTime(),
            'fuel_rest'                     => $spaceSheep->getFuelRest(),
            'galaxy'                        => $spaceSheep->getGalaxy()         ? $spaceSheep->getGalaxy()->getId()         : null,
            'planetSystem'                  => $spaceSheep->getPlanetSystem()   ? $spaceSheep->getPlanetSystem()->getId()   : null,
            'star'                          => $spaceSheep->getStar()           ? $spaceSheep->getStar()->getId()           : null,
            'planet'                        => $spaceSheep->getPlanet()         ? $spaceSheep->getPlanet()->getId()         : null,
            'sputnik'                       => $spaceSheep->getSputnik()        ? $spaceSheep->getSputnik()->getId()        : null,
            'owner'                         => $spaceSheep->getOwner()          ? $spaceSheep->getOwner()->getId()          : null,
            'event'                         => $spaceSheep->getEvent()          ? $spaceSheep->getEvent()->getId()          : null
        ]);
        $update->where(['id = ?' => $spaceSheep->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during spaceSheep update operation'
            );
        }

        return $spaceSheep;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $spaceSheep)
    {
        if (! $spaceSheep->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($spaceSheep->getTable());
        $delete->where(['id = ?' => $spaceSheep->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
