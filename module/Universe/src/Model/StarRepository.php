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

class StarRepository implements EntityRepositoryInterface
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
     * @var Star
     */
    private $starPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Star $starPrototype
    ) {
        $this->db                       = $db;
        $this->hydrator                 = $hydrator;
        $this->starPrototype            = $starPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Star instance.
     *
     * @return Star[]
     */
    public function findAllEntities($criteria='')
    {
        $sql            = new Sql($this->db);
        $select         = $sql->select('stars')
            ->join(['ps' => 'planet_system'], 'stars.planet_system = ps.id',
                [
                    'planet_system.id'          => 'id',
                    'planet_system.name'        => 'name',
                    'planet_system.description' => 'description',
                    'planet_system.basis'       => 'basis',
                    'planet_system.size'        => 'size',
                    'planet_system.star'        => 'star',
                    'planet_system.galaxy'      => 'galaxy'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars_types'], 'stars.star_type = st.id',
                [
                    'stars_types.id'             => 'id',
                    'stars_types.name'           => 'name',
                    'stars_types.description'    => 'description',
                    'stars_types.color_eng'      => 'color_eng',
                    'stars_types.color_rus'      => 'color_rus',
                    'stars_types.kelvin_min'     => 'kelvin_min',
                    'stars_types.kelvin_max'     => 'kelvin_max',
                    'stars_types.star_class'     => 'star_class',
                    'stars_types.part'           => 'part'
                ],
                Select::JOIN_LEFT);
        if ($criteria) {
            $select->where($criteria);
        }
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->starPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id, $criteria='')
    {
        $sql    = new Sql($this->db);
        $select         = $sql->select('stars')
            ->join(['ps' => 'planet_system'], 'stars.planet_system = ps.id',
                [
                    'planet_system.id'          => 'id',
                    'planet_system.name'        => 'name',
                    'planet_system.description' => 'description',
                    'planet_system.basis'       => 'basis',
                    'planet_system.size'        => 'size',
                    'planet_system.star'        => 'star',
                    'planet_system.galaxy'      => 'galaxy'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars_types'], 'stars.star_type = st.id',
                [
                    'stars_types.id'             => 'id',
                    'stars_types.name'           => 'name',
                    'stars_types.description'    => 'description',
                    'stars_types.color_eng'      => 'color_eng',
                    'stars_types.color_rus'      => 'color_rus',
                    'stars_types.kelvin_min'     => 'kelvin_min',
                    'stars_types.kelvin_max'     => 'kelvin_max',
                    'stars_types.star_class'     => 'star_class',
                    'stars_types.part'           => 'part'
                ],
                Select::JOIN_LEFT);
        $select->where($criteria ? $criteria : ['stars.id = ?' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving Star with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->starPrototype);
        $resultSet->initialize($result);
        $star = $resultSet->current();

        if (! $star) {
            throw new InvalidArgumentException(sprintf(
                'Star with identifier "%s" not found.',
                $id
            ));
        }

        return $star;
    }
    
    public function findBy($criteria, $orderBy='', $limit='', $offset='')
    {
        return $this->findAllEntities($criteria);
    }
    
    public function findOneBy($criteria, $orderBy='')
    {
        return $this->findEntity(0, $criteria);
    }
    
}
