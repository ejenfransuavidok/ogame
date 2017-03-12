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

class SputnikRepository implements EntityRepositoryInterface
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
     * @var Sputnik
     */
    private $sputnikPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Sputnik $sputnikPrototype
    ) {
        $this->db                       = $db;
        $this->hydrator                 = $hydrator;
        $this->sputnikPrototype         = $sputnikPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Sputnik instance.
     *
     * @return Sputnik[]
     */
    public function findAllEntities($criteria='')
    {
        $sql            = new Sql($this->db);
        $select         = $sql->select('sputniks')
            ->join(['ps' => 'planet_system'], 'sputniks.planet_system = ps.id',
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
            ->join(['p' => 'planets'], 'sputniks.parent_planet = p.id',
                [
                    'planets.id'                => 'id',
                    'planets.name'              => 'name',
                    'planets.description'       => 'description',
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
                    'planets.mineral_anti'      => 'mineral_anti'
                ]);
        if($criteria) {
            $select->where($criteria);
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->sputnikPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id, $criteria='')
    {
        $sql    = new Sql($this->db);
        $select         = $sql->select('sputniks')
            ->join(['ps' => 'planet_system'], 'sputniks.planet_system = ps.id',
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
            ->join(['p' => 'planets'], 'sputniks.parent_planet = p.id',
                [
                    'planets.id'                => 'id',
                    'planets.name'              => 'name',
                    'planets.description'       => 'description',
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
                    'planets.mineral_anti'      => 'mineral_anti'
                ]);
                
        $select->where($criteria ? $criteria : ['sputniks.id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving Sputnik with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->sputnikPrototype);
        $resultSet->initialize($result);
        $sputnik = $resultSet->current();

        if (! $sputnik) {
            throw new InvalidArgumentException(sprintf(
                'Sputnik with identifier "%s" not found.',
                $id
            ));
        }

        return $sputnik;
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
