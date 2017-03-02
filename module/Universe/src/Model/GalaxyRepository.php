<?php

namespace Universe\Model;

use InvalidArgumentException;
use RuntimeException;
// Replace the import of the Reflection hydrator with this:
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class GalaxyRepository implements EntityRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var Galaxy
     */
    private $galaxyPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Galaxy $galaxyPrototype
    ) {
        $this->db                   = $db;
        $this->hydrator             = $hydrator;
        $this->galaxyPrototype      = $galaxyPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Galaxy instance.
     *
     * @return Galaxy[]
     */
    public function findAllEntities($criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('galaxies');
        if($criteria) {
            $select->where($criteria);
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->galaxyPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id, $criteria='')
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('galaxies');
        $select->where($criteria ? $criteria : ['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving galaxy with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->galaxyPrototype);
        $resultSet->initialize($result);
        $galaxy = $resultSet->current();

        if (! $galaxy) {
            throw new InvalidArgumentException(sprintf(
                'Galaxy with identifier "%s" not found.',
                $id
            ));
        }

        return $galaxy;
    }
    
    public function findBy($criteria, $orderBy='', $limit='', $offset='')
    {
        return $this->findAllEntities($criteria);
    }
    
    public function findOneBy($criteria, $orderBy='')
    {
        return $this->findEntity(0, $criteria);
    }

}
