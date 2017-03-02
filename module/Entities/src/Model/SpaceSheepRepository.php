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
    public function findAllEntities($value='', $field='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('spacesheeps')
            ->join(['gx' => 'galaxies'], 'spacesheeps.galaxy = gx.id',
                [
                    'galaxies.id'               => 'id',
                    'galaxies.name'             => 'name',
                    'galaxies.description'      => 'descriptiption',
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
                    'planets.coordinate'        => 'coordinate',
                    'planets.size'              => 'size',
                    'planets.position'          => 'position',
                    'planets.livable'           => 'livable',
                    'planets.planet_system'     => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'spacesheeps.sputnik = sp.id',
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
                ]);
        if ($field && $value) {
            $select->where(['spacesheeps.'.$field.' = ?' => $value]);
        }
            
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->spaceSheepPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('spacesheeps')
            ->join(['gx' => 'galaxies'], 'spacesheeps.galaxy = gx.id',
                [
                    'galaxies.id'               => 'id',
                    'galaxies.name'             => 'name',
                    'galaxies.description'      => 'descriptiption',
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
                    'planets.coordinate'        => 'coordinate',
                    'planets.size'              => 'size',
                    'planets.position'          => 'position',
                    'planets.livable'           => 'livable',
                    'planets.planet_system'     => 'planet_system'
                ],
                Select::JOIN_LEFT)
            ->join(['sp' => 'sputniks'], 'spacesheeps.sputnik = sp.id',
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
                ]);
        $select->where(['id = ?' => $id]);

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

}
