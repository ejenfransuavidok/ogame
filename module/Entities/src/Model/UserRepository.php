<?php

namespace Entities\Model;

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
use Universe\Model\EntityRepositoryInterface;

class UserRepository implements EntityRepositoryInterface
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
     * @var User
     */
    private $userPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        User $userPrototype
    ) {
        $this->db             = $db;
        $this->hydrator       = $hydrator;
        $this->userPrototype  = $userPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a User instance.
     *
     * @return User[]
     */
    public function findAllEntities($criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('users')
            ->join(['gx' => 'galaxies'], 'users.galaxy = gx.id',
                [
                    'galaxies.id'                 => 'id',
                    'galaxies.name'               => 'name',
                    'galaxies.description'        => 'description',
                    'galaxies.basis'              => 'basis',
                    'galaxies.size'               => 'size'
                ],
                Select::JOIN_LEFT)
            ->join(['ps' => 'planet_system'], 'users.planet_system = ps.id',
                [
                    'planet_system.id'            => 'id',
                    'planet_system.name'          => 'name',
                    'planet_system.description'   => 'description',
                    'planet_system.basis'         => 'basis',
                    'planet_system.size'          => 'size',
                    'planet_system.star'          => 'star',
                    'planet_system.galaxy'        => 'galaxy'
                ],
                Select::JOIN_LEFT)
            ->join(['p' => 'planets'], 'users.planet = p.id',
                [
                    'planets.id'            => 'id',
                    'planets.name'          => 'name',
                    'planets.description'   => 'description',
                    'planets.coordinate'    => 'coordinate',
                    'planets.size'          => 'size',
                    'planets.position'      => 'position',
                    'planets.livable'       => 'livable',
                    'planets.planet_system' => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'users.sputnik = sp.id',
                [
                    'sputniks.id'           => 'id',
                    'sputniks.name'         => 'name',
                    'sputniks.description'  => 'description',
                    'sputniks.size'         => 'size',
                    'sputniks.position'     => 'position',
                    'sputniks.distance'     => 'distance',
                    'sputniks.parent_planet'=> 'parent_planet',
                    'sputniks.planet_system'=> 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'users.star = st.id',
                [
                    'stars.id'              => 'id',
                    'stars.name'            => 'name',
                    'stars.description'     => 'description',
                    'stars.coordinate'      => 'coordinate',
                    'stars.size'            => 'size',
                    'stars.star_type'       => 'star_type',
                    'stars.planet_system'   => 'planet_system'
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

        $resultSet = new HydratingResultSet($this->hydrator, $this->userPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id, $criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('users')
            ->join(['gx' => 'galaxies'], 'users.galaxy = gx.id',
                [
                    'galaxies.id'                 => 'id',
                    'galaxies.name'               => 'name',
                    'galaxies.description'        => 'description',
                    'galaxies.basis'              => 'basis',
                    'galaxies.size'               => 'size'
                ],
                Select::JOIN_LEFT)
            ->join(['ps' => 'planet_system'], 'users.planet_system = ps.id',
                [
                    'planet_system.id'            => 'id',
                    'planet_system.name'          => 'name',
                    'planet_system.description'   => 'description',
                    'planet_system.basis'         => 'basis',
                    'planet_system.size'          => 'size',
                    'planet_system.star'          => 'star',
                    'planet_system.galaxy'        => 'galaxy'
                ],
                Select::JOIN_LEFT)
            ->join(['p' => 'planets'], 'users.planet = p.id',
                [
                    'planets.id'            => 'id',
                    'planets.name'          => 'name',
                    'planets.description'   => 'description',
                    'planets.coordinate'    => 'coordinate',
                    'planets.size'          => 'size',
                    'planets.position'      => 'position',
                    'planets.livable'       => 'livable',
                    'planets.planet_system' => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'users.sputnik = sp.id',
                [
                    'sputniks.id'           => 'id',
                    'sputniks.name'         => 'name',
                    'sputniks.description'  => 'description',
                    'sputniks.size'         => 'size',
                    'sputniks.position'     => 'position',
                    'sputniks.distance'     => 'distance',
                    'sputniks.parent_planet'=> 'parent_planet',
                    'sputniks.planet_system'=> 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'users.star = st.id',
                [
                    'stars.id'              => 'id',
                    'stars.name'            => 'name',
                    'stars.description'     => 'description',
                    'stars.coordinate'      => 'coordinate',
                    'stars.size'            => 'size',
                    'stars.star_type'       => 'star_type',
                    'stars.planet_system'   => 'planet_system'
                ],
                Select::JOIN_LEFT);
        $select->where($criteria ? $criteria : ['id = ?' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving user with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->userPrototype);
        $resultSet->initialize($result);
        $user = $resultSet->current();

        if (!$user) {
            throw new InvalidArgumentException(sprintf(
                'User with identifier "%s" not found.',
                $id
            ));
        }

        return $user;
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
