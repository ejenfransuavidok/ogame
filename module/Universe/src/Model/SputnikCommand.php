<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class SputnikCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE sputniks');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $sputnik)
    {
        $insert = new Insert($sputnik->GetTable());
        $insert->values([
            'name'          => $sputnik->getName(),
            'description'   => $sputnik->getDescription(),
            'size'          => $sputnik->getSize(),
            'position'      => $sputnik->getPosition(),
            'distance'      => $sputnik->getDistance(),
            'parent_planet' => $sputnik->GetParentPlanet()->getId(),
            'planet_system' => $sputnik->getCelestialParent()->getId()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during sputnik insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Sputnik(
            $sputnik->getName(),
            $sputnik->getDescription(),
            $sputnik->getSize(),
            $sputnik->getPosition(),
            $sputnik->getDistance(),
            $sputnik->GetParentPlanet(),
            $sputnik->getCelestialParent(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $sputnik)
    {
        if (! $sputnik->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($sputnik->GetTable());
        $update->set([
                'name'          => $sputnik->getName(),
                'description'   => $sputnik->getDescription(),
                'size'          => $sputnik->getSize(),
                'position'      => $sputnik->getPosition(),
                'distance'      => $sputnik->getDistance(),
                'parent_planet' => $sputnik->GetParentPlanet->getId(),
                'planet_system' => $sputnik->getCelestialParent()->getId()
        ]);
        $update->where(['id = ?' => $sputnik->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during sputnik update operation'
            );
        }

        return $sputnik;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $sputnik)
    {
        if (! $sputnik->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($sputnik->getTable());
        $delete->where(['id = ?' => $sputnik->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
