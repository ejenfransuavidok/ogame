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

class TechnologyConnectionCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE technologies_connections');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $technologyConnection)
    {
        $insert = new Insert($technologyConnection->GetTable());
        $insert->values([
            'tech_1'          => $technologyConnection->getTech_1()->getId(),
            'tech_2'          => $technologyConnection->getTech_2()->getId(),
            'conn'            => $technologyConnection->getConn()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during TechnologyConnection insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new TechnologyConnection(
            $technologyConnection->getTech_1(),
            $technologyConnection->getTech_2(),
            $technologyConnection->getConn(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $technologyConnection)
    {
        if (! $technologyConnection->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($technologyConnection->GetTable());
        $update->set([
            'tech_1'          => $technologyConnection->getTech_1()->getId(),
            'tech_2'          => $technologyConnection->getTech_2()->getId(),
            'conn'            => $technologyConnection->getConn()
        ]);
        $update->where(['id = ?' => $technologyConnection->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during TechnologyConnection update operation'
            );
        }

        return $technologyConnection;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $technologyConnection)
    {
        if (! $technologyConnection->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($technologyConnection->getTable());
        $delete->where(['id = ?' => $technologyConnection->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
