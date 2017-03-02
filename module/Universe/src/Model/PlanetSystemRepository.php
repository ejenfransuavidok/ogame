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

class PlanetSystemRepository implements EntityRepositoryInterface
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
     * @var PlanetSystem
     */
    private $planetSystemPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        PlanetSystem $planetSystemPrototype
    ) {
        $this->db                       = $db;
        $this->hydrator                 = $hydrator;
        $this->planetSystemPrototype    = $planetSystemPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a PlanetSystem instance.
     *
     * @return PlanetSystem[]
     */
    public function findAllEntities()
    {
        $sql            = new Sql($this->db);
        $select = $sql->select('planet_system')
            ->join(['gx' => 'galaxies'], 'planet_system.galaxy = gx.id',
                [
                    'galaxies.id'          => 'id',
                    'galaxies.name'        => 'name',
                    'galaxies.description' => 'description',
                    'galaxies.basis'       => 'basis',
                    'galaxies.size'        => 'size'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'planet_system.star = st.id',
                [
                    'stars.id'             => 'id',
                    'stars.name'           => 'name',
                    'stars.description'    => 'description',
                    'stars.coordinate'     => 'coordinate',
                    'stars.size'           => 'coordinate',
                    'stars.star_type'      => 'star_type',
                    'stars.planet_system'  => 'planet_system'
                ],
                Select::JOIN_LEFT);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->planetSystemPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id)
    {
        $sql    = new Sql($this->db);
        $select = $sql->select('planet_system')
            ->join(['gx' => 'galaxies'], 'planet_system.galaxy = gx.id',
                [
                    'galaxies.id'          => 'id',
                    'galaxies.name'        => 'name',
                    'galaxies.description' => 'description',
                    'galaxies.basis'       => 'basis',
                    'galaxies.size'        => 'size'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'planet_system.star = st.id',
                [
                    'stars.id'             => 'id',
                    'stars.name'           => 'name',
                    'stars.description'    => 'description',
                    'stars.coordinate'     => 'coordinate',
                    'stars.size'           => 'coordinate',
                    'stars.star_type'      => 'star_type',
                    'stars.planet_system'  => 'planet_system'
                ],
                Select::JOIN_LEFT);
                
        $select->where(['planet_system.id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving planet_system with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->planetSystemPrototype);
        $resultSet->initialize($result);
        $planet_system = $resultSet->current();

        if (! $planet_system) {
            throw new InvalidArgumentException(sprintf(
                'PlanetSystem with identifier "%s" not found.',
                $id
            ));
        }

        return $planet_system;
    }

}
