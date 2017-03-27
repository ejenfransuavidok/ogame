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

class SpaceSheepRepository implements EntityRepositoryInterface
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
     * @var SpaceSheep
     */
    private $spaceSheepPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        SpaceSheep $spaceSheepPrototype
    ) {
        $this->db                   = $db;
        $this->hydrator             = $hydrator;
        $this->spaceSheepPrototype  = $spaceSheepPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a SpaceSheep instance.
     *
     * @return SpaceSheep[]
     */
    public function findAllEntities($criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('spacesheeps')
            ->join(['gx' => 'galaxies'], 'spacesheeps.galaxy = gx.id',
                [
                    'galaxies.id'               => 'id',
                    'galaxies.name'             => 'name',
                    'galaxies.description'      => 'description',
                    'galaxies.basis'            => 'basis',
                    'galaxies.size'             => 'size'
                ],
                Select::JOIN_LEFT)
            ->join(['ps' => 'planet_system'], 'spacesheeps.planetSystem = ps.id',
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
            ->join(['p' => 'planets'], 'spacesheeps.planet = p.id',
                [
                    'planets.id'                => 'id',
                    'planets.name'              => 'name',
                    'planets.description'       => 'description',
                    'planets.type'              => 'type',
                    'planets.coordinate'        => 'coordinate',
                    'planets.size'              => 'size',
                    'planets.position'          => 'position',
                    'planets.livable'           => 'livable',
                    'planets.planet_system'     => 'planet_system',
                    'planets.mineral_metall'    => 'mineral_metall',
                    'planets.mineral_heavygas'  => 'mineral_heavygas',
                    'planets.mineral_ore'       => 'mineral_ore',
                    'planets.mineral_hydro'     => 'mineral_hydro',
                    'planets.mineral_titan'     => 'mineral_titan',
                    'planets.mineral_darkmatter'=> 'mineral_darkmatter',
                    'planets.mineral_redmatter' => 'mineral_redmatter',
                    'planets.mineral_anti'      => 'mineral_anti',
                    'planets.electricity'       => 'electricity',
                    'planets.owner'             => 'owner'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'spacesheeps.sputnik = sp.id',
                [
                    'sputniks.id'                => 'id',
                    'sputniks.name'              => 'name',
                    'sputniks.description'       => 'description',
                    'sputniks.type'              => 'type',
                    'sputniks.size'              => 'size',
                    'sputniks.position'          => 'position',
                    'sputniks.distance'          => 'distance',
                    'sputniks.parent_planet'     => 'parent_planet',
                    'sputniks.planet_system'     => 'planet_system',
                    'sputniks.mineral_metall'    => 'mineral_metall',
                    'sputniks.mineral_heavygas'  => 'mineral_heavygas',
                    'sputniks.mineral_ore'       => 'mineral_ore',
                    'sputniks.mineral_hydro'     => 'mineral_hydro',
                    'sputniks.mineral_titan'     => 'mineral_titan',
                    'sputniks.mineral_darkmatter'=> 'mineral_darkmatter',
                    'sputniks.mineral_redmatter' => 'mineral_redmatter',
                    'sputniks.mineral_anti'      => 'mineral_anti',
                    'sputniks.electricity'       => 'electricity',
                    'sputniks.owner'             => 'owner'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'spacesheeps.star = st.id',
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
            ->join(['u' => 'users'], 'spacesheeps.owner = u.id',
                [
                    'users.id'                      => 'id',
                    'users.login'                   => 'login',
                    'users.password'                => 'password',
                    'users.email'                   => 'email',
                    'users.firstname'               => 'firstname',
                    'users.description'             => 'description',
                    'users.lastname'                => 'lastname',
                    'users.amount_of_metall'        => 'amount_of_metall',
                    'users.amount_of_heavygas'      => 'amount_of_heavygas',
                    'users.amount_of_ore'           => 'amount_of_ore',
                    'users.amount_of_hydro'         => 'amount_of_hydro',
                    'users.amount_of_titan'         => 'amount_of_titan',
                    'users.amount_of_darkmatter'    => 'amount_of_darkmatter',
                    'users.amount_of_redmatter'     => 'amount_of_redmatter',
                    'users.amount_of_anti'          => 'amount_of_anti',
                    'users.amount_of_electricity'   => 'amount_of_electricity',
                    'users.money'                   => 'money'
                ],
                Select::JOIN_LEFT)
            ->join(['ev' => 'events'], 'spacesheeps.event = ev.id',
                [
                    'events.id'                 => 'id',
                    'events.name'               => 'name',
                    'events.description'        => 'description',
                    'events.user'               => 'user',
                    'events.event_type'         => 'event_type',
                    'events.event_begin'        => 'event_begin',
                    'events.event_end'          => 'event_end',
                    'events.target_star'        => 'target_star',
                    'events.target_planet'      => 'target_planet',
                    'events.target_sputnik'     => 'target_sputnik'
                ],
                Select::JOIN_LEFT);
        if($criteria) {
            $select->where($criteria);
        }
        
        $statement = $sql->prepareStatementForSqlObject($select); //die( $select->getSqlString());
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->spaceSheepPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id, $criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('spacesheeps')
            ->join(['gx' => 'galaxies'], 'spacesheeps.galaxy = gx.id',
                [
                    'galaxies.id'               => 'id',
                    'galaxies.name'             => 'name',
                    'galaxies.description'      => 'description',
                    'galaxies.basis'            => 'basis',
                    'galaxies.size'             => 'size'
                ],
                Select::JOIN_LEFT)
            ->join(['ps' => 'planet_system'], 'spacesheeps.planet_system = ps.id',
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
            ->join(['p' => 'planets'], 'spacesheeps.planet = p.id',
                [
                    'planets.id'                => 'id',
                    'planets.name'              => 'name',
                    'planets.description'       => 'description',
                    'planets.type'              => 'type',
                    'planets.coordinate'        => 'coordinate',
                    'planets.size'              => 'size',
                    'planets.position'          => 'position',
                    'planets.livable'           => 'livable',
                    'planets.planet_system'     => 'planet_system',
                    'planets.mineral_metall'    => 'mineral_metall',
                    'planets.mineral_heavygas'  => 'mineral_heavygas',
                    'planets.mineral_ore'       => 'mineral_ore',
                    'planets.mineral_hydro'     => 'mineral_hydro',
                    'planets.mineral_titan'     => 'mineral_titan',
                    'planets.mineral_darkmatter'=> 'mineral_darkmatter',
                    'planets.mineral_redmatter' => 'mineral_redmatter',
                    'planets.mineral_anti'      => 'mineral_anti',
                    'planets.electricity'       => 'electricity',
                    'planets.owner'             => 'owner'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'spacesheeps.sputnik = sp.id',
                [
                    'sputniks.id'                => 'id',
                    'sputniks.name'              => 'name',
                    'sputniks.description'       => 'description',
                    'sputniks.type'              => 'type',
                    'sputniks.size'              => 'size',
                    'sputniks.position'          => 'position',
                    'sputniks.distance'          => 'distance',
                    'sputniks.parent_planet'     => 'parent_planet',
                    'sputniks.planet_system'     => 'planet_system',
                    'sputniks.mineral_metall'    => 'mineral_metall',
                    'sputniks.mineral_heavygas'  => 'mineral_heavygas',
                    'sputniks.mineral_ore'       => 'mineral_ore',
                    'sputniks.mineral_hydro'     => 'mineral_hydro',
                    'sputniks.mineral_titan'     => 'mineral_titan',
                    'sputniks.mineral_darkmatter'=> 'mineral_darkmatter',
                    'sputniks.mineral_redmatter' => 'mineral_redmatter',
                    'sputniks.mineral_anti'      => 'mineral_anti',
                    'sputniks.electricity'       => 'electricity',
                    'sputniks.owner'             => 'owner'
                ],
                Select::JOIN_LEFT)
            ->join(['st' => 'stars'], 'spacesheeps.star = st.id',
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
            ->join(['u' => 'users'], 'spacesheeps.owner = u.id',
                [
                    'users.id'                      => 'id',
                    'users.login'                   => 'login',
                    'users.password'                => 'password',
                    'users.email'                   => 'email',
                    'users.firstname'               => 'firstname',
                    'users.description'             => 'description',
                    'users.lastname'                => 'lastname',
                    'users.amount_of_metall'        => 'amount_of_metall',
                    'users.amount_of_heavygas'      => 'amount_of_heavygas',
                    'users.amount_of_ore'           => 'amount_of_ore',
                    'users.amount_of_hydro'         => 'amount_of_hydro',
                    'users.amount_of_titan'         => 'amount_of_titan',
                    'users.amount_of_darkmatter'    => 'amount_of_darkmatter',
                    'users.amount_of_redmatter'     => 'amount_of_redmatter',
                    'users.amount_of_anti'          => 'amount_of_anti',
                    'users.amount_of_electricity'   => 'amount_of_electricity',
                    'users.money'                   => 'money'
                ],
                Select::JOIN_LEFT)
            ->join(['ev' => 'events'], 'spacesheeps.event = ev.id',
                [
                    'events.id'                 => 'id',
                    'events.name'               => 'name',
                    'events.description'        => 'description',
                    'events.user'               => 'user',
                    'events.event_type'         => 'event_type',
                    'events.event_begin'        => 'event_begin',
                    'events.event_end'          => 'event_end',
                    'events.target_star'        => 'target_star',
                    'events.target_planet'      => 'target_planet',
                    'events.target_sputnik'     => 'target_sputnik'
                ],
                Select::JOIN_LEFT);
        $select->where($criteria ? $criteria : ['spacesheeps.id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving spaceSheep with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->spaceSheepPrototype);
        $resultSet->initialize($result);
        $spaceSheep = $resultSet->current();

        if (! $spaceSheep) {
            throw new InvalidArgumentException(sprintf(
                'SpaceSheep with identifier "%s" not found.',
                $id
            ));
        }

        return $spaceSheep;
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
