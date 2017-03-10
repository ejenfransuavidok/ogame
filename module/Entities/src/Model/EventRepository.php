<?php

namespace Entities\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Universe\Model\EntityRepositoryInterface;

class EventRepository implements EntityRepositoryInterface
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
     * @var Event
     */
    private $eventPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Event $eventPrototype
    ) {
        $this->db              = $db;
        $this->hydrator        = $hydrator;
        $this->eventPrototype  = $eventPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Event instance.
     *
     * @return Event[]
     */
    public function findAllEntities($criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('events')
            ->join(['u' => 'users'], 'events.user = u.id',
                [
                    'users.id'                  => 'id',
                    'users.login'               => 'login',
                    'users.password'            => 'password',
                    'users.email'               => 'email',
                    'users.firstname'           => 'firstname',
                    'users.galaxy'              => 'galaxy',
                    'users.planet_system'       => 'planet_system',
                    'users.planet'              => 'planet',
                    'users.sputnik'             => 'sputnik',
                    'users.star'                => 'star',
                    'users.description'         => 'description'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'events.target_star = st.id',
                [
                    'stars.id'                  => 'id',
                    'stars.name'                => 'name',
                    'stars.description'         => 'description',
                    'stars.coordinate'          => 'coordinate',
                    'stars.size'                => 'size',
                    'stars.star_type'           => 'star_type',
                    'stars.planet_system'       => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['p' => 'planets'], 'events.target_planet = p.id',
                [
                    'planets.id'                => 'id',
                    'planets.name'              => 'name',
                    'planets.description'       => 'description',
                    'planets.coordinate'        => 'coordinate',
                    'planets.size'              => 'size',
                    'planets.position'          => 'position',
                    'planets.livable'           => 'livable',
                    'planets.planet_system'     => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'events.target_sputnik = sp.id',
                [
                    'sputniks.id'               => 'id',
                    'sputniks.name'             => 'name',
                    'sputniks.description'      => 'description',
                    'sputniks.size'             => 'size',
                    'sputniks.position'         => 'position',
                    'sputniks.distance'         => 'distance',
                    'sputniks.parent_planet'    => 'parent_planet',
                    'sputniks.planet_system'    => 'planet_system'
                ],
                Select::JOIN_LEFT);
        if($criteria) {
            $select->where($criteria);
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }
        $resultSet = new HydratingResultSet($this->hydrator, $this->eventPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id, $criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('events')
            ->join(['u' => 'users'], 'events.user = u.id',
                [
                    'users.id'                  => 'id',
                    'users.login'               => 'login',
                    'users.password'            => 'password',
                    'users.email'               => 'email',
                    'users.firstname'           => 'firstname',
                    'users.galaxy'              => 'galaxy',
                    'users.planet_system'       => 'planet_system',
                    'users.planet'              => 'planet',
                    'users.sputnik'             => 'sputnik',
                    'users.star'                => 'star',
                    'users.description'         => 'description'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'events.target_star = st.id',
                [
                    'stars.id'                  => 'id',
                    'stars.name'                => 'name',
                    'stars.description'         => 'description',
                    'stars.coordinate'          => 'coordinate',
                    'stars.size'                => 'size',
                    'stars.star_type'           => 'star_type',
                    'stars.planet_system'       => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['p' => 'planets'], 'events.target_planet = p.id',
                [
                    'planets.id'                => 'id',
                    'planets.name'              => 'name',
                    'planets.description'       => 'description',
                    'planets.coordinate'        => 'coordinate',
                    'planets.size'              => 'size',
                    'planets.position'          => 'position',
                    'planets.livable'           => 'livable',
                    'planets.planet_system'     => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'events.target_sputnik = sp.id',
                [
                    'sputniks.id'               => 'id',
                    'sputniks.name'             => 'name',
                    'sputniks.description'      => 'description',
                    'sputniks.size'             => 'size',
                    'sputniks.position'         => 'position',
                    'sputniks.distance'         => 'distance',
                    'sputniks.parent_planet'    => 'parent_planet',
                    'sputniks.planet_system'    => 'planet_system'
                ],
                Select::JOIN_LEFT);
        $select->where($criteria ? $criteria : ['events.id = ?' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving event with identifier "%s"; unknown database error.',
                $id
            ));
        }
        $resultSet = new HydratingResultSet($this->hydrator, $this->eventPrototype);
        $resultSet->initialize($result);
        $event = $resultSet->current();

        if (! $event) {
            throw new InvalidArgumentException(sprintf(
                'Event with identifier "%s" not found.',
                $id
            ));
        }
        return $event;
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
