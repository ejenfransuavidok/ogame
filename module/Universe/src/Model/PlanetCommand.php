<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class PlanetCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE planets');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $planet)
    {
        $insert = new Insert($planet->GetTable());
        $insert->values([
            'name'                                  => $planet->getName(),
            'description'                           => $planet->getDescription(),
            'type'                                  => $planet->getType() ? $planet->getType()->getId() : null,
            'coordinate'                            => $planet->getCoordinate(),
            'size'                                  => $planet->getSize(),
            'position'                              => $planet->getPosition(),
            'livable'                               => $planet->getLivable(),
            'planet_system'                         => $planet->getCelestialParent()->getId(),
            'mineral_metall'                        => $planet->getMetall(),
            'mineral_heavygas'                      => $planet->getHeavyGas(),
            'mineral_ore'                           => $planet->getOre(),
            'mineral_hydro'                         => $planet->getHydro(),
            'mineral_titan'                         => $planet->getTitan(),
            'mineral_darkmatter'                    => $planet->getDarkmatter(),
            'mineral_redmatter'                     => $planet->getRedmatter(),
            'mineral_anti'                          => $planet->getAnti(),
            'electricity'                           => $planet->getElectricity(),
            'velocity_per_second_mineral_metall'    => $planet->get_velocity_per_second_mineral_metall(),
            'velocity_per_second_mineral_heavygas'  => $planet->get_velocity_per_second_mineral_heavygas(),
            'velocity_per_second_mineral_ore'       => $planet->get_velocity_per_second_mineral_ore(),
            'velocity_per_second_mineral_hydro'     => $planet->get_velocity_per_second_mineral_hydro(),
            'velocity_per_second_mineral_titan'     => $planet->get_velocity_per_second_mineral_titan(),
            'velocity_per_second_mineral_darkmatter'=> $planet->get_velocity_per_second_mineral_darkmatter(),
            'velocity_per_second_mineral_redmatter' => $planet->get_velocity_per_second_mineral_redkmatter(),
            'velocity_per_second_mineral_anti'      => $planet->get_velocity_per_second_mineral_anti(),
            'update'                                => $planet->getUpdate(),
            'owner'                                 => $planet->getOwner() ? $planet->getOwner()->getId() : null
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during planet insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Planet(
            $planet->getName(),
            $planet->getDescription(),
            $planet->getType(),
            $planet->getCoordinate(),
            $planet->getSize(),
            $planet->getPosition(),
            $planet->getLivable(),
            $planet->getCelestialParent(),
            $planet->getMetall(),
            $planet->getHeavyGas(),
            $planet->getOre(),
            $planet->getHydro(),
            $planet->getTitan(),
            $planet->getDarkmatter(),
            $planet->getRedmatter(),
            $planet->getAnti(),
            $planet->getElectricity(),
            $planet->get_velocity_per_second_mineral_metall(),
            $planet->get_velocity_per_second_mineral_heavygas(),
            $planet->get_velocity_per_second_mineral_ore(),
            $planet->get_velocity_per_second_mineral_hydro(),
            $planet->get_velocity_per_second_mineral_titan(),
            $planet->get_velocity_per_second_mineral_darkmatter(),
            $planet->get_velocity_per_second_mineral_redkmatter(),
            $planet->get_velocity_per_second_mineral_anti(),
            $planet->getUpdate(),
            $planet->getOwner(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $planet)
    {
        if (! $planet->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($planet->GetTable());
        $update->set([
            'name'                                  => $planet->getName(),
            'description'                           => $planet->getDescription(),
            'type'                                  => $planet->getType() ? $planet->getType()->getId() : null,
            'coordinate'                            => $planet->getCoordinate(),
            'size'                                  => $planet->getSize(),
            'position'                              => $planet->getPosition(),
            'livable'                               => $planet->getLivable(),
            'planet_system'                         => $planet->getCelestialParent()->getId(),
            'mineral_metall'                        => $planet->getMetall(),
            'mineral_heavygas'                      => $planet->getHeavyGas(),
            'mineral_ore'                           => $planet->getOre(),
            'mineral_hydro'                         => $planet->getHydro(),
            'mineral_titan'                         => $planet->getTitan(),
            'mineral_darkmatter'                    => $planet->getDarkmatter(),
            'mineral_redmatter'                     => $planet->getRedmatter(),
            'mineral_anti'                          => $planet->getAnti(),
            'electricity'                           => $planet->getElectricity(),
            'velocity_per_second_mineral_metall'    => $planet->get_velocity_per_second_mineral_metall(),
            'velocity_per_second_mineral_heavygas'  => $planet->get_velocity_per_second_mineral_heavygas(),
            'velocity_per_second_mineral_ore'       => $planet->get_velocity_per_second_mineral_ore(),
            'velocity_per_second_mineral_hydro'     => $planet->get_velocity_per_second_mineral_hydro(),
            'velocity_per_second_mineral_titan'     => $planet->get_velocity_per_second_mineral_titan(),
            'velocity_per_second_mineral_darkmatter'=> $planet->get_velocity_per_second_mineral_darkmatter(),
            'velocity_per_second_mineral_redmatter' => $planet->get_velocity_per_second_mineral_redmatter(),
            'velocity_per_second_mineral_anti'      => $planet->get_velocity_per_second_mineral_anti(),
            'update'                                => $planet->getUpdate(),
            'owner'                                 => $planet->getOwner() ? $planet->getOwner()->getId() : null
        ]);
        $update->where(['planets.id = ?' => $planet->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during planet update operation'
            );
        }

        return $planet;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $planet)
    {
        if (! $planet->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($planet->getTable());
        $delete->where(['id = ?' => $planet->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
