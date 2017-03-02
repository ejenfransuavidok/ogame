<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class StarCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE stars');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $star)
    {
        $insert = new Insert($star->GetTable());
                
        $insert->values([
            'name'          => $star->getName(),
            'description'   => $star->getDescription(),
            'coordinate'    => $star->getCoordinate(),
            'size'          => $star->getSize(),
            'star_type'     => $star->getStarType()->getId(),
            'planet_system' => $star->getCelestialParent()->getId()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during star insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Star(
            $star->getName(),
            $star->getDescription(),
            $star->getCoordinate(),
            $star->getSize(),
            $star->getStarType(),
            $star->getCelestialParent(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $star)
    {
        if (! $star->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($star->GetTable());
        $update->set([
                'name'          => $star->getName(),
                'description'   => $star->getDescription(),
                'coordinate'    => $star->getCoordinate(),
                'size'          => $star->getSize(),
                'star_type'     => $star->getStarType()->getId(),
                'planet_system' => $star->getCelestialParent()->getId()
        ]);
        $update->where(['id = ?' => $star->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during star update operation'
            );
        }

        return $star;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $star)
    {
        if (! $star->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($star->getTable());
        $delete->where(['id = ?' => $star->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
