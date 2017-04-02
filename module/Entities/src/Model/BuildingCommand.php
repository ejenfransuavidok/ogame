<?php
namespace Entities\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Universe\Model\EntityCommandInterface;
use Universe\Model\Entity;

class BuildingCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE buildings');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $building)
    {
        $insert = new Insert($building->GetTable());
        $insert->values([
            'name'                 => $building->getName(),
            'description'          => $building->getDescription(),
            'planet'               => $building->getPlanet()    ?   $building->getPlanet()->getId()     : null,
            'sputnik'              => $building->getSputnik()   ?   $building->getSputnik()->getId()    : null,
            'owner'                => $building->getOwner()     ?   $building->getOwner()->getId()      : null,
            'level'                => $building->getLevel(),
            'type'                 => $building->getType(),
            'update'               => $building->getUpdate(),
            'factor'               => $building->getFactor(),
            'produce_metall'       => $building->getProduceMetall(),
            'produce_heavygas'     => $building->getProduceHeavygas(),
            'produce_ore'          => $building->getProduceOre(),
            'produce_hydro'        => $building->getProduceHydro(),
            'produce_titan'        => $building->getProduceTitan(),
            'produce_darkmatter'   => $building->getProduceDarkmatter(),
            'produce_redmatter'    => $building->getProduceRedmatter(),
            'produce_anti'         => $building->getProduceAnti(),
            'produce_electricity'  => $building->getProduceElectricity(),
            'consume_metall'       => $building->getConsumeMetall(),
            'consume_heavygas'     => $building->getConsumeHeavygas(),
            'consume_ore'          => $building->getConsumeOre(),
            'consume_hydro'        => $building->getConsumeHydro(),
            'consume_titan'        => $building->getConsumeTitan(),
            'consume_darkmatter'   => $building->getConsumeDarkmatter(),
            'consume_redmatter'    => $building->getConsumeRedmatter(),
            'consume_anti'         => $building->getConsumeAnti(),
            'consume_electricity'  => $building->getConsumeElectricity(),
            'capacity_metall'      => $building->getCapacityMetall(),
            'capacity_heavygas'    => $building->getCapacityHeavygas(),
            'capacity_ore'         => $building->getCapacityOre(),
            'capacity_hydro'       => $building->getCapacityHydro(),
            'capacity_titan'       => $building->getCapacityTitan(),
            'capacity_darkmatter'  => $building->getCapacityDarkmatter(),
            'capacity_redmatter'   => $building->getCapacityRedmatter()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during building insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Building(
            $building->getName(),
            $building->getDescription(),
            $building->getPlanet(),
            $building->getSputnik(),
            $building->getOwner(),
            $building->getLevel(),
            $building->getType(),
            $building->getUpdate(),
            $building->getFactor(),
            $building->getProduceMetall(),
            $building->getProduceHeavygas(),
            $building->getProduceOre(),
            $building->getProduceHydro(),
            $building->getProduceTitan(),
            $building->getProduceDarkmatter(),
            $building->getProduceRedmatter(),
            $building->getProduceAnti(),
            $building->getProduceElectricity(),
            $building->getConsumeMetall(),
            $building->getConsumeHeavygas(),
            $building->getConsumeOre(),
            $building->getConsumeHydro(),
            $building->getConsumeTitan(),
            $building->getConsumeDarkmatter(),
            $building->getConsumeAnti(),
            $building->getConsumeRedmatter(),
            $building->getConsumeElectricity(),
            $building->getCapacityMetall(),
            $building->getCapacityHeavygas(),
            $building->getCapacityOre(),
            $building->getCapacityHydro(),
            $building->getCapacityTitan(),
            $building->getCapacityDarkmatter(),
            $building->getCapacityRedmatter(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $building)
    {
        if (! $building->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($building->GetTable());
        $update->set([
            'name'                 => $building->getName(),
            'description'          => $building->getDescription(),
            'planet'               => $building->getPlanet()    ?   $building->getPlanet()->getId()     : null,
            'sputnik'              => $building->getSputnik()   ?   $building->getSputnik()->getId()    : null,
            'owner'                => $building->getOwner()     ?   $building->getOwner()->getId()      : null,
            'level'                => $building->getLevel(),
            'type'                 => $building->getType(),
            'update'               => $building->getUpdate(),
            'factor'               => $building->getFactor(),
            'produce_metall'       => $building->getProduceMetall(),
            'produce_heavygas'     => $building->getProduceHeavygas(),
            'produce_ore'          => $building->getProduceOre(),
            'produce_hydro'        => $building->getProduceHydro(),
            'produce_titan'        => $building->getProduceTitan(),
            'produce_darkmatter'   => $building->getProduceDarkmatter(),
            'produce_redmatter'    => $building->getProduceRedmatter(),
            'produce_anti'         => $building->getProduceAnti(),
            'produce_electricity'  => $building->getProduceElectricity(),
            'consume_metall'       => $building->getConsumeMetall(),
            'consume_heavygas'     => $building->getConsumeHeavygas(),
            'consume_ore'          => $building->getConsumeOre(),
            'consume_hydro'        => $building->getConsumeHydro(),
            'consume_titan'        => $building->getConsumeTitan(),
            'consume_darkmatter'   => $building->getConsumeDarkmatter(),
            'consume_redmatter'    => $building->getConsumeRedmatter(),
            'consume_anti'         => $building->getConsumeAnti(),
            'consume_electricity'  => $building->getConsumeElectricity(),
            'capacity_metall'      => $building->getCapacityMetall(),
            'capacity_heavygas'    => $building->getCapacityHeavygas(),
            'capacity_ore'         => $building->getCapacityOre(),
            'capacity_hydro'       => $building->getCapacityHydro(),
            'capacity_titan'       => $building->getCapacityTitan(),
            'capacity_darkmatter'  => $building->getCapacityDarkmatter(),
            'capacity_redmatter'   => $building->getCapacityRedmatter()
        ]);
        $update->where(['id = ?' => $building->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during building update operation'
            );
        }

        return $building;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $building)
    {
        if (! $building->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($building->getTable());
        $delete->where(['id = ?' => $building->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
