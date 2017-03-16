<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class PlanetSystemCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE planet_system');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $planet_system)
    {
        $insert = new Insert($planet_system->GetTable());
        $insert->values([
            'name'          => $planet_system->getName(),
            'description'   => $planet_system->getDescription(),
            'basis'         => $planet_system->getBasis(),
            'size'          => $planet_system->getSize(),
            'star'          => $planet_system->getStar()->getId(),
            'galaxy'        => $planet_system->getGalaxy()->getId(),
            'index'         => $planet_system->getIndex()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during planet_system insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new PlanetSystem(
            $planet_system->getName(),
            $planet_system->getDescription(),
            $planet_system->getBasis(),
            $planet_system->getSize(),
            $planet_system->getStar(),
            $planet_system->getGalaxy(),
            $planet_system->getIndex(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $planet_system)
    {
        if (! $planet_system->getId() ) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }
        $update = new Update($planet_system->GetTable());
        $update->set([
                'name'          => $planet_system->getName(),
                'description'   => $planet_system->getDescription(),
                'basis'         => $planet_system->getBasis(),
                'size'          => $planet_system->getSize(),
                'star'          => $planet_system->getStar()->getId(),
                'galaxy'        => $planet_system->getGalaxy()->getId(),
                'index'         => $planet_system->getIndex()
        ]);
        $update->where(['id = ?' => $planet_system->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during planet_system update operation'
            );
        }

        return $planet_system;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $planet_system)
    {
        if (! $planet_system->getId()) {
            throw RuntimeException('Cannot update planet_system; missing identifier');
        }

        $delete = new Delete($planet_system->getTable());
        $delete->where(['id = ?' => $planet_system->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
