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

class StarTypeRepository implements EntityRepositoryInterface
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
     * @var Settings
     */
    private $starTypePrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        StarType $starTypePrototype
    ) {
        $this->db                   = $db;
        $this->hydrator             = $hydrator;
        $this->starTypePrototype    = $starTypePrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a StarType instance.
     *
     * @return StarType[]
     */
    public function findAllEntities()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('stars_types');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->starTypePrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('stars_types');
        $select->where(['id = ?' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        
        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving star type with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->starTypePrototype);
        $resultSet->initialize($result);
        $star_type = $resultSet->current();

        if (! $star_type) {
            throw new InvalidArgumentException(sprintf(
                'Star type with identifier "%s" not found.',
                $id
            ));
        }

        return $star_type;
    }

}
