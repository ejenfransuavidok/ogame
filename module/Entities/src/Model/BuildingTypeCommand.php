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
            'name'                 => $buildingType->getName(),
            'description'          => $buildingType->getDescription(),
            'type'                 => $buildingType->getType(),
            'factor'               => $buildingType->getFactor(),
            'produce_metall'       => $buildingType->getProduceMetall(),
            'produce_heavygas'     => $buildingType->getProduceHeavygas(),
            'produce_ore'          => $buildingType->getProduceOre(),
            'produce_hydro'        => $buildingType->getProduceHydro(),
            'produce_titan'        => $buildingType->getProduceTitan(),
            'produce_darkmatter'   => $buildingType->getProduceDarkmatter(),
            'produce_redmatter'    => $buildingType->getProduceRedmatter(),
            'produce_anti'         => $buildingType->getProduceAnti(),
            'produce_electricity'  => $buildingType->getProduceElectricity(),
            'consume_metall'       => $buildingType->getConsumeMetall(),
            'consume_heavygas'     => $buildingType->getConsumeHeavygas(),
            'consume_ore'          => $buildingType->getConsumeOre(),
            'consume_hydro'        => $buildingType->getConsumeHydro(),
            'consume_titan'        => $buildingType->getConsumeTitan(),
            'consume_darkmatter'   => $buildingType->getConsumeDarkmatter(),
            'consume_redmatter'    => $buildingType->getConsumeRedmatter(),
            'consume_anti'         => $buildingType->getConsumeAnti(),
            'consume_electricity'  => $buildingType->getConsumeElectricity(),
            'picture'              => $buildingType->getPicture()
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
            $buildingType->getDescription(),
            $buildingType->getType(),
            $buildingType->getFactor(),
            $buildingType->getProduceMetall(),
            $buildingType->getProduceHeavygas(),
            $buildingType->getProduceOre(),
            $buildingType->getProduceHydro(),
            $buildingType->getProduceTitan(),
            $buildingType->getProduceDarkmatter(),
            $buildingType->getProduceRedmatter(),
            $buildingType->getProduceAnti(),
            $buildingType->getProduceElectricity(),
            $buildingType->getConsumeMetall(),
            $buildingType->getConsumeHeavygas(),
            $buildingType->getConsumeOre(),
            $buildingType->getConsumeHydro(),
            $buildingType->getConsumeTitan(),
            $buildingType->getConsumeDarkmatter(),
            $buildingType->getConsumeAnti(),
            $buildingType->getConsumeRedmatter(),
            $buildingType->getConsumeElectricity(),
            $buildingType->getPicture(),
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
            'name'                 => $buildingType->getName(),
            'description'          => $buildingType->getDescription(),
            'type'                 => $buildingType->getType(),
            'factor'               => $buildingType->getFactor(),
            'produce_metall'       => $buildingType->getProduceMetall(),
            'produce_heavygas'     => $buildingType->getProduceHeavygas(),
            'produce_ore'          => $buildingType->getProduceOre(),
            'produce_hydro'        => $buildingType->getProduceHydro(),
            'produce_titan'        => $buildingType->getProduceTitan(),
            'produce_darkmatter'   => $buildingType->getProduceDarkmatter(),
            'produce_redmatter'    => $buildingType->getProduceRedmatter(),
            'produce_anti'         => $buildingType->getProduceAnti(),
            'produce_electricity'  => $buildingType->getProduceElectricity(),
            'consume_metall'       => $buildingType->getConsumeMetall(),
            'consume_heavygas'     => $buildingType->getConsumeHeavygas(),
            'consume_ore'          => $buildingType->getConsumeOre(),
            'consume_hydro'        => $buildingType->getConsumeHydro(),
            'consume_titan'        => $buildingType->getConsumeTitan(),
            'consume_darkmatter'   => $buildingType->getConsumeDarkmatter(),
            'consume_redmatter'    => $buildingType->getConsumeRedmatter(),
            'consume_anti'         => $buildingType->getConsumeAnti(),
            'consume_electricity'  => $buildingType->getConsumeElectricity(),
            'picture'              => $buildingType->getPicture()
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
