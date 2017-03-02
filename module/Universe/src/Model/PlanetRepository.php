<?php

namespace Universe\Model;

use InvalidArgumentException;
use RuntimeException;
// Replace the import of the Reflection hydrator with this:
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class PlanetRepository implements EntityRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var Planet
     */
    private $planetPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Planet $planetPrototype
    ) {
        $this->db                       = $db;
        $this->hydrator                 = $hydrator;
        $this->planetPrototype          = $planetPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Planet instance.
     *
     * @return Planet[]
     */
    public function findAllEntities()
    {
        $sql            = new Sql($this->db);
        $select         = $sql->select('planets')
            ->join(['ps' => 'planet_system'], 'planets.planet_system = ps.id',
                [
                    'planet_system.id'          => 'id',
                    'planet_system.name'        => 'name',
                    'planet_system.description' => 'description',
                    'planet_system.basis'       => 'basis',
                    'planet_system.size'        => 'size',
                    'planet_system.star'        => 'star',
                    'planet_system.galaxy'      => 'galaxy'
                ],
                Select::JOIN_LEFT);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->planetPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id)
    {
        $sql    = new Sql($this->db);
        $select         = $sql->select('planets')
            ->join(['ps' => 'planet_system'], 'planets.planet_system = ps.id',
                [
                    'planet_system.id'          => 'id',
                    'planet_system.name'        => 'name',
                    'planet_system.description' => 'description',
                    'planet_system.basis'       => 'basis',
                    'planet_system.size'        => 'size',
                    'planet_system.star'        => 'star',
                    'planet_system.galaxy'      => 'galaxy'
                ],
                Select::JOIN_LEFT);
                
        $select->where(['planets.id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving Planet with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->planetPrototype);
        $resultSet->initialize($result);
        $planet = $resultSet->current();

        if (! $planet) {
            throw new InvalidArgumentException(sprintf(
                'Planet with identifier "%s" not found.',
                $id
            ));
        }

        return $planet;
    }

}
