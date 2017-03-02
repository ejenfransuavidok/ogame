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

class UserCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE users');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $user)
    {
        $insert = new Insert($user->GetTable());
        $insert->values([
            'login'         => $user->getName(),
            'description'   => $user->getDescription(),
            'password'      => $user->getPassword(),
            'firstname'     => $user->getFirstName(),
            'lastname'      => $user->getLastName(),
            'galaxy'        => $user->getGalaxy()->getId(),
            'planet_system' => $user->getPlanetSystem()->getId(),
            'planet'        => $user->getPlanet()->getId(),
            'sputnik'       => $user->getSputnik()->getId(),
            'star'          => $user->getStar()->getId()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during user insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new User(
            $user->getName(),
            $user->getDescription(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $user)
    {
        if (! $user->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($user->GetTable());
        $update->set([
                'name'          => $user->getName(),
                'description'   => $user->getDescription()
        ]);
        $update->where(['id = ?' => $user->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during user update operation'
            );
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $user)
    {
        if (! $user->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($user->getTable());
        $delete->where(['id = ?' => $user->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
