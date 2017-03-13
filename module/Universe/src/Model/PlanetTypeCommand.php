<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class PlanetTypeCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE planets_types');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $planet_type)
    {
        $insert = new Insert($planet_type->GetTable());
        $insert->values([
            'name'              => $planet_type->getName(),
            'description'       => $planet_type->getDescription(),
            'probability'       => $planet_type->getProbability(),
            'mineral_metall'    => $planet_type->getMetall(),
            'mineral_heavygas'  => $planet_type->getHeavyGas(),
            'mineral_ore'       => $planet_type->getOre(),
            'mineral_hydro'     => $planet_type->getHydro(),
            'mineral_titan'     => $planet_type->getTitan(),
            'mineral_darkmatter'=> $planet_type->getDarkmatter(),
            'mineral_redmatter' => $planet_type->getRedmatter(),
            'mineral_anti'      => $planet_type->getAnti()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during planet_type insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new PlanetType(
            $planet_type->getName(),
            $planet_type->getDescription(),
            $planet_type->getProbability(),
            $planet_type->getMetall(),
            $planet_type->getHeavyGas(),
            $planet_type->getOre(),
            $planet_type->getHydro(),
            $planet_type->getTitan(),
            $planet_type->getDarkmatter(),
            $planet_type->getRedmatter(),
            $planet_type->getAnti(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $planet_type)
    {
        if (! $planet_type->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($planet_type->GetTable());
        $update->set([
                'name'              => $planet_type->getName(),
                'description'       => $planet_type->getDescription(),
                'probability'       => $planet_type->getProbability(),
                'mineral_metall'    => $planet_type->getMetall(),
                'mineral_heavygas'  => $planet_type->getHeavyGas(),
                'mineral_ore'       => $planet_type->getOre(),
                'mineral_hydro'     => $planet_type->getHydro(),
                'mineral_titan'     => $planet_type->getTitan(),
                'mineral_darkmatter'=> $planet_type->getDarkmatter(),
                'mineral_redmatter' => $planet_type->getRedmatter(),
                'mineral_anti'      => $planet_type->getAnti()
        ]);
        $update->where(['id = ?' => $planet_type->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during planet_type update operation'
            );
        }

        return $planet_type;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $planet_type)
    {
        if (! $planet_type->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($planet_type->getTable());
        $delete->where(['id = ?' => $planet_type->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
