<?php

namespace Entities\Model;

use InvalidArgumentException;
use RuntimeException;
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

class TechnologyConnectionRepository implements EntityRepositoryInterface
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
     * @var TechnologyConnection
     */
    private $technologyConnectionPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Technology $technologyConnectionPrototype
    ) {
        $this->db                               = $db;
        $this->hydrator                         = $hydrator;
        $this->technologyConnectionPrototype    = $technologyPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a TechnologyConnection instance.
     *
     * @return TechnologyConnection[]
     */
    public function findAllEntities()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('technologies_connections');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->technologyConnectionPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function findEntity($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('technologies_connections');
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving TechnologyConnection with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->technologyConnectionPrototype);
        $resultSet->initialize($result);
        $technologyConnection = $resultSet->current();

        if (! $technologyConnection) {
            throw new InvalidArgumentException(sprintf(
                'TechnologyConnection with identifier "%s" not found.',
                $id
            ));
        }

        return $technologyConnection;
    }

}
