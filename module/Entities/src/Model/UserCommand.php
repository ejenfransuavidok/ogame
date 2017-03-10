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
            'email'         => $user->getEmail(),
            'firstname'     => $user->getFirstName(),
            'lastname'      => $user->getLastName(),
            'galaxy'        => $user->getGalaxy()        ? $user->getGalaxy()->getId()          : null,
            'planet_system' => $user->getPlanetSystem()  ? $user->getPlanetSystem()->getId()    : null,
            'planet'        => $user->getPlanet()        ? $user->getPlanet()->getId()          : null,
            'sputnik'       => $user->getSputnik()       ? $user->getSputnik()->getId()         : null,
            'star'          => $user->getStar()          ? $user->getStar()->getId()            : null
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
            $user->getPassword(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getGalaxy(),
            $user->getPlanetSystem(),
            $user->getPlanet(),
            $user->getSputnik(),
            $user->getStar(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $user)
    {
        if (! $user->getId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($user->GetTable());
        $update->set([
            'login'         => $user->getName(),
            'description'   => $user->getDescription(),
            'password'      => $user->getPassword(),
            'email'         => $user->getEmail(),
            'firstname'     => $user->getFirstName(),
            'lastname'      => $user->getLastName(),
            'galaxy'        => $user->getGalaxy()       ? $user->getGalaxy()->getId()       : null,
            'planet_system' => $user->getPlanetSystem() ? $user->getPlanetSystem()->getId() : null,
            'planet'        => $user->getPlanet()       ? $user->getPlanet()->getId()       : null,
            'sputnik'       => $user->getSputnik()      ? $user->getSputnik()->getId()      : null,
            'star'          => $user->getStar()         ? $user->getStar()->getId()         : null
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
            throw new RuntimeException('Cannot update entity; missing identifier');
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
