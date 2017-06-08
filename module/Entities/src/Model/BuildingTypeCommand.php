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

class BuildingTypeCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE building_types');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $buildingType)
    {
        $insert = new Insert($buildingType->GetTable());
        $insert->values([
            'name'                                     => $buildingType->getName(),
            'object_type'                              => $buildingType->getObjectType(),
            'building_type'                            => $buildingType->getBuildingType(),
            'price_factor'                             => $buildingType->getPriceFactor(),
            'picture'                                  => $buildingType->getPicture(),
            'description'                              => $buildingType->getDescription(),
            'power_factor'                             => $buildingType->getPowerFactor(),
            'save_factor'                              => $buildingType->getSaveFactor(),
            'maxlevel'                                 => $buildingType->getMaxlevel(),
            'multiple'                                 => $buildingType->getMultiple(),
            'destroed'                                 => $buildingType->getDestroed(),
            ////////////////////////////////////////////
            'consume_metall'                           => $buildingType->getConsumeMetall(),
            'consume_heavygas'                         => $buildingType->getConsumeHeavygas(),
            'consume_ore'                              => $buildingType->getConsumeOre(),
            'consume_hydro'                            => $buildingType->getConsumeHydro(),
            'consume_titan'                            => $buildingType->getConsumeTitan(),
            'consume_darkmatter'                       => $buildingType->getConsumeDarkmatter(),
            'consume_redmatter'                        => $buildingType->getConsumeRedmatter(),
            'consume_anti'                             => $buildingType->getConsumeAnti(),
            'consume_electricity'                      => $buildingType->getConsumeElectricity(),
            ////////////////////////////////////////////
            'produce_metall'                           => $buildingType->getProduceMetall(),
            'produce_heavygas'                         => $buildingType->getProduceHeavygas(),
            'produce_ore'                              => $buildingType->getProduceOre(),
            'produce_hydro'                            => $buildingType->getProduceHydro(),
            'produce_titan'                            => $buildingType->getProduceTitan(),
            'produce_darkmatter'                       => $buildingType->getProduceDarkmatter(),
            'produce_redmatter'                        => $buildingType->getProduceRedmatter(),
            'produce_anti'                             => $buildingType->getProduceAnti(),
            'produce_electricity'                      => $buildingType->getProduceElectricity(),
            ////////////////////////////////////////////
            'capacity_metall'                          => $buildingType->getCapacityMetall(),
            'capacity_heavygas'                        => $buildingType->getCapacityHeavygas(),
            'capacity_ore'                             => $buildingType->getCapacityOre(),
            'capacity_hydro'                           => $buildingType->getCapacityHydro(),
            'capacity_titan'                           => $buildingType->getCapacityTitan(),
            'capacity_darkmatter'                      => $buildingType->getCapacityDarkmatter(),
            'capacity_redmatter'                       => $buildingType->getCapacityRedmatter(),
            ////////////////////////////////////////////
            'building_acceleration_factor'             => $buildingType->getBuildingAccelerationFactor(),
            'defence_acceleration_factor'              => $buildingType->getDefenceAccelerationFactor(),
            'smallsmallsheeps_acceleration_factor'     => $buildingType->getSmallSmallSheepsAccelerationFactor(),
            'smallsheeps_acceleration_factor'          => $buildingType->getSmallSheepsAccelerationFactor(),
            'averagesheeps_acceleration_factor'        => $buildingType->getAverageSheepsAccelerationFactor(),
            'bigsheeps_acceleration_factor'            => $buildingType->getBigSheepsAccelerationFactor(),
            'giantsheeps_acceleration_factor'          => $buildingType->getGiantSheepsAccelerationFactor(),
            'wartech_acceleration_factor'              => $buildingType->getWartechAccelerationFactor(),
            'civiltech_acceleration_factor'            => $buildingType->getCiviltechAccelerationFactor(),
            'enginetech_acceleration_factor'           => $buildingType->getEnginetechAccelerationFactor()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during buildingType insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new BuildingType(
            $buildingType->getName(),
            $buildingType->getObjectType(),
            $buildingType->getBuildingType(),
            $buildingType->getPriceFactor(),
            $buildingType->getPicture(),
            $buildingType->getDescription(),
            $buildingType->getPowerFactor(),
            $buildingType->getSaveFactor(),
            $buildingType->getMaxlevel(),
            $buildingType->getMultiple(),
            $buildingType->getDestroed(),
            ////////////////////////////////////////////
            $buildingType->getConsumeMetall(),
            $buildingType->getConsumeHeavygas(),
            $buildingType->getConsumeOre(),
            $buildingType->getConsumeHydro(),
            $buildingType->getConsumeTitan(),
            $buildingType->getConsumeDarkmatter(),
            $buildingType->getConsumeRedmatter(),
            $buildingType->getConsumeAnti(),
            $buildingType->getConsumeElectricity(),
            ////////////////////////////////////////////
            $buildingType->getProduceMetall(),
            $buildingType->getProduceHeavygas(),
            $buildingType->getProduceOre(),
            $buildingType->getProduceHydro(),
            $buildingType->getProduceTitan(),
            $buildingType->getProduceDarkmatter(),
            $buildingType->getProduceRedmatter(),
            $buildingType->getProduceAnti(),
            $buildingType->getProduceElectricity(),
            ////////////////////////////////////////////
            $buildingType->getCapacityMetall(),
            $buildingType->getCapacityHeavygas(),
            $buildingType->getCapacityOre(),
            $buildingType->getCapacityHydro(),
            $buildingType->getCapacityTitan(),
            $buildingType->getCapacityDarkmatter(),
            $buildingType->getCapacityRedmatter(),
            ////////////////////////////////////////////
            $buildingType->getBuildingAccelerationFactor(),
            $buildingType->getDefenceAccelerationFactor(),
            $buildingType->getSmallSmallSheepsAccelerationFactor(),
            $buildingType->getSmallSheepsAccelerationFactor(),
            $buildingType->getAverageSheepsAccelerationFactor(),
            $buildingType->getBigSheepsAccelerationFactor(),
            $buildingType->getGiantSheepsAccelerationFactor(),
            $buildingType->getWartechAccelerationFactor(),
            $buildingType->getCiviltechAccelerationFactor(),
            $buildingType->getEnginetechAccelerationFactor(),
            
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $buildingType)
    {
        if (! $buildingType->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($buildingType->GetTable());
        $update->set([
            'name'                                     => $buildingType->getName(),
            'object_type'                              => $buildingType->getObjectType(),
            'building_type'                            => $buildingType->getBuildingType(),
            'price_factor'                             => $buildingType->getPriceFactor(),
            'picture'                                  => $buildingType->getPicture(),
            'description'                              => $buildingType->getDescription(),
            'power_factor'                             => $buildingType->getPowerFactor(),
            'save_factor'                              => $buildingType->getSaveFactor(),
            'maxlevel'                                 => $buildingType->getMaxlevel(),
            'multiple'                                 => $buildingType->getMultiple(),
            'destroed'                                 => $buildingType->getDestroed(),
            ////////////////////////////////////////////
            'consume_metall'                           => $buildingType->getConsumeMetall(),
            'consume_heavygas'                         => $buildingType->getConsumeHeavygas(),
            'consume_ore'                              => $buildingType->getConsumeOre(),
            'consume_hydro'                            => $buildingType->getConsumeHydro(),
            'consume_titan'                            => $buildingType->getConsumeTitan(),
            'consume_darkmatter'                       => $buildingType->getConsumeDarkmatter(),
            'consume_redmatter'                        => $buildingType->getConsumeRedmatter(),
            'consume_anti'                             => $buildingType->getConsumeAnti(),
            'consume_electricity'                      => $buildingType->getConsumeElectricity(),
            ////////////////////////////////////////////
            'produce_metall'                           => $buildingType->getProduceMetall(),
            'produce_heavygas'                         => $buildingType->getProduceHeavygas(),
            'produce_ore'                              => $buildingType->getProduceOre(),
            'produce_hydro'                            => $buildingType->getProduceHydro(),
            'produce_titan'                            => $buildingType->getProduceTitan(),
            'produce_darkmatter'                       => $buildingType->getProduceDarkmatter(),
            'produce_redmatter'                        => $buildingType->getProduceRedmatter(),
            'produce_anti'                             => $buildingType->getProduceAnti(),
            'produce_electricity'                      => $buildingType->getProduceElectricity(),
            ////////////////////////////////////////////
            'capacity_metall'                          => $buildingType->getCapacityMetall(),
            'capacity_heavygas'                        => $buildingType->getCapacityHeavygas(),
            'capacity_ore'                             => $buildingType->getCapacityOre(),
            'capacity_hydro'                           => $buildingType->getCapacityHydro(),
            'capacity_titan'                           => $buildingType->getCapacityTitan(),
            'capacity_darkmatter'                      => $buildingType->getCapacityDarkmatter(),
            'capacity_redmatter'                       => $buildingType->getCapacityRedmatter(),
            ////////////////////////////////////////////
            'building_acceleration_factor'             => $buildingType->getBuildingAccelerationFactor(),
            'defence_acceleration_factor'              => $buildingType->getDefenceAccelerationFactor(),
            'smallsmallsheeps_acceleration_factor'     => $buildingType->getSmallSmallSheepsAccelerationFactor(),
            'smallsheeps_acceleration_factor'          => $buildingType->getSmallSheepsAccelerationFactor(),
            'averagesheeps_acceleration_factor'        => $buildingType->getAverageSheepsAccelerationFactor(),
            'bigsheeps_acceleration_factor'            => $buildingType->getBigSheepsAccelerationFactor(),
            'giantsheeps_acceleration_factor'          => $buildingType->getGiantSheepsAccelerationFactor(),
            'wartech_acceleration_factor'              => $buildingType->getWartechAccelerationFactor(),
            'civiltech_acceleration_factor'            => $buildingType->getCiviltechAccelerationFactor(),
            'enginetech_acceleration_factor'           => $buildingType->getEnginetechAccelerationFactor()
        ]);
        $update->where(['id = ?' => $buildingType->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during buildingType update operation'
            );
        }

        return $buildingType;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $buildingType)
    {
        if (! $buildingType->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($buildingType->getTable());
        $delete->where(['id = ?' => $buildingType->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
