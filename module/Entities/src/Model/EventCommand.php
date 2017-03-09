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

class EventCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE events');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $event)
    {
        $insert = new Insert($event->GetTable());
        $insert->values([
            'name'            => $event->getName(),
            'description'     => $event->getDescription(),
            'user'            => $event->getUser() ? $event->getUser()->getId() : null,
            'event_type'      => $event->getEventType(),
            'event_begin'     => $event->getEventBegin(),
            'event_end'       => $event->getEventEnd(),
            'target_star'     => $event->getTargetStar() ? $event->getTargetStar()->getId() : null,
            'target_planet'   => $event->getTargetPlanet() ? $event->getTargetPlanet()->getId() : null,
            'target_sputnik'  => $event->getTargetSputnik() ? $event->getTargetSputnik()->getId() : null
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during Event insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Event(
            $event->getName(),
            $event->getDescription(),
            $event->getUser(),
            $event->getEventType(),
            $event->getEventBegin(),
            $event->getEventEnd(),
            $event->getTargetStar(),
            $event->getTargetPlanet(),
            $event->getTargetSputnik(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $event)
    {
        if (! $event->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($event->GetTable());
        $update->set([
            'name'          => $event->getName(),
            'description'   => $event->getDescription(),
            'user'          => $event->getUser() ? $event->getUser()->getId() : null,
            'event_type'    => $event->getEventType(),
            'event_begin'   => $event->getEventBegin(),
            'event_end'     => $event->getEventEnd(),
            'star'          => $event->getTargetStar() ? $event->getTargetStar()->getId() : null,
            'planet'        => $event->getTargetPlanet() ? $event->getTargetPlanet()->getId() : null,
            'sputnik'       => $event->getTargetSputnik() ? $event->getTargetSputnik()->getId() : null
        ]);
        $update->where(['id = ?' => $event->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during event update operation'
            );
        }

        return $event;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $event)
    {
        if (! $event->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($event->getTable());
        $delete->where(['id = ?' => $event->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
