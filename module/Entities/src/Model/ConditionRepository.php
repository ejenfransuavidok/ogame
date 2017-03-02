<?php

namespace Entities\Model;

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
use Universe\Model\EntityRepositoryInterface;

class ConditionRepository implements EntityRepositoryInterface
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
     * @var Condition
     */
    private $conditionPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Condition $conditionPrototype
    ) {
        $this->db                   = $db;
        $this->hydrator             = $hydrator;
        $this->conditionPrototype  = $conditionPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Condition instance.
     *
     * @return Condition[]
     */
    public function findAllEntities()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('conditions');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->conditionPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('conditions');
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving condition with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->conditionPrototype);
        $resultSet->initialize($result);
        $condition = $resultSet->current();

        if (! $condition) {
            throw new InvalidArgumentException(sprintf(
                'Condition with identifier "%s" not found.',
                $id
            ));
        }

        return $condition;
    }

}
