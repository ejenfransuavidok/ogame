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
            ->join(['sp' => 'sputniks'], 'events.target_sputnik = sp.id',
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
            ->join(['bt' => 'building_types'], 'events.targetBuildingType = bt.id',
                [
                    'building_types.id'                                       => 'id',
                    'building_types.name'                                     => 'name',
                    'building_types.object_type'                              => 'object_type',
                    'building_types.building_type'                            => 'building_type',
                    'building_types.price_factor'                             => 'price_factor',
                    'building_types.picture'                                  => 'picture',
                    'building_types.description'                              => 'description',
                    'building_types.power_factor'                             => 'power_factor',
                    'building_types.save_factor'                              => 'save_factor',
                    'building_types.maxlevel'                                 => 'maxlevel',
                    'building_types.multiple'                                 => 'multiple',
                    'building_types.destroed'                                 => 'destroed',
                    ////////////////////////////////////////////
                    'building_types.consume_metall'                           => 'consume_metall',
                    'building_types.consume_heavygas'                         => 'consume_heavygas',
                    'building_types.consume_ore'                              => 'consume_ore',
                    'building_types.consume_hydro'                            => 'consume_hydro',
                    'building_types.consume_titan'                            => 'consume_titan',
                    'building_types.consume_darkmatter'                       => 'consume_darkmatter',
                    'building_types.consume_redmatter'                        => 'consume_redmatter',
                    'building_types.consume_anti'                             => 'consume_anti',
                    'building_types.consume_electricity'                      => 'consume_electricity',
                    ////////////////////////////////////////////
                    'building_types.produce_metall'                           => 'produce_metall',
                    'building_types.produce_heavygas'                         => 'produce_heavygas',
                    'building_types.produce_ore'                              => 'produce_ore',
                    'building_types.produce_hydro'                            => 'produce_hydro',
                    'building_types.produce_titan'                            => 'produce_titan',
                    'building_types.produce_darkmatter'                       => 'produce_darkmatter',
                    'building_types.produce_redmatter'                        => 'produce_redmatter',
                    'building_types.produce_anti'                             => 'produce_anti',
                    'building_types.produce_electricity'                      => 'produce_electricity',
                    ////////////////////////////////////////////
                    'building_types.capacity_metall'                          => 'capacity_metall',
                    'building_types.capacity_heavygas'                        => 'capacity_heavygas',
                    'building_types.capacity_ore'                             => 'capacity_ore',
                    'building_types.capacity_hydro'                           => 'capacity_hydro',
                    'building_types.capacity_titan'                           => 'capacity_titan',
                    'building_types.capacity_darkmatter'                      => 'capacity_darkmatter',
                    'building_types.capacity_redmatter'                       => 'capacity_redmatter',
                    ////////////////////////////////////////////
                    'building_types.building_acceleration_factor'             => 'building_acceleration_factor',
                    'building_types.defence_acceleration_factor'              => 'defence_acceleration_factor',
                    'building_types.smallsmallsheeps_acceleration_factor'     => 'smallsmallsheeps_acceleration_factor',
                    'building_types.smallsheeps_acceleration_factor'          => 'smallsheeps_acceleration_factor',
                    'building_types.averagesheeps_acceleration_factor'        => 'averagesheeps_acceleration_factor',
                    'building_types.bigsheeps_acceleration_factor'            => 'bigsheeps_acceleration_factor',
                    'building_types.giantsheeps_acceleration_factor'          => 'giantsheeps_acceleration_factor',
                    'building_types.wartech_acceleration_factor'              => 'wartech_acceleration_factor',
                    'building_types.civiltech_acceleration_factor'            => 'civiltech_acceleration_factor',
                    'building_types.enginetech_acceleration_factor'           => 'enginetech_acceleration_factor'
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
            ->join(['sp' => 'sputniks'], 'events.target_sputnik = sp.id',
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
            ->join(['bt' => 'building_types'], 'events.targetBuildingType = bt.id',
                [
                    'building_types.id'                 => 'id',
                    'building_types.name'               => 'name',
                    'building_types.description'        => 'description',
                    'building_types.type'               => 'type',
                    'building_types.produce_metall'     => 'produce_metall',
                    'building_types.produce_heavygas'   => 'produce_heavygas',
                    'building_types.produce_ore'        => 'produce_ore',
                    'building_types.produce_hydro'      => 'produce_hydro',
                    'building_types.produce_titan'      => 'produce_titan',
                    'building_types.produce_darkmatter' => 'produce_darkmatter',
                    'building_types.produce_redmatter'  => 'produce_redmatter',
                    'building_types.produce_anti'       => 'produce_anti',
                    'building_types.produce_electricity'=> 'produce_electricity',
                    'building_types.consume_metall'     => 'consume_metall',
                    'building_types.consume_heavygas'   => 'consume_heavygas',
                    'building_types.consume_ore'        => 'consume_ore',
                    'building_types.consume_hydro'      => 'consume_hydro',
                    'building_types.consume_titan'      => 'consume_titan',
                    'building_types.consume_darkmatter' => 'consume_darkmatter',
                    'building_types.consume_redmatter'  => 'consume_redmatter',
                    'building_types.consume_anti'       => 'consume_anti',
                    'building_types.consume_electricity'=> 'consume_electricity',
                    'building_types.picture'            => 'picture'
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
