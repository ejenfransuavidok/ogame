<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class GalaxyCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE galaxies');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $galaxy)
    {
        $insert = new Insert($galaxy->GetTable());
        $insert->values([
            'name'          => $galaxy->getName(),
            'description'   => $galaxy->getDescription(),
            'basis'         => $galaxy->getBasis(),
            'size'          => $galaxy->getSize()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during galaxy insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Galaxy(
            $galaxy->getName(),
            $galaxy->getDescription(),
            $galaxy->getBasis(),
            $galaxy->getSize(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $galaxy)
    {
        if (! $galaxy->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($galaxy->GetTable());
        $update->set([
                'name'          => $galaxy->getName(),
                'description'   => $galaxy->getDescription(),
                'basis'         => $galaxy->getBasis(),
                'size'          => $galaxy->getSize()
        ]);
        $update->where(['id = ?' => $galaxy->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during galaxy update operation'
            );
        }

        return $galaxy;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $galaxy)
    {
        if (! $galaxy->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($galaxy->getTable());
        $delete->where(['id = ?' => $galaxy->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
