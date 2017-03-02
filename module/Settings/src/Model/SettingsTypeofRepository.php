<?php

// In module/Settings/src/Model/ZendDbSqlRepository.php:
namespace Settings\Model;

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

class SettingsTypeofRepository implements SettingsTypeofRepositoryInterface
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
     * @var SettingsTypeof
     */
    private $settingsTypeofPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        SettingsTypeof $settingsTypeofPrototype
    ) {
        $this->db               = $db;
        $this->hydrator         = $hydrator;
        $this->settingsTypeofPrototype = $settingsTypeofPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Settings instance.
     *
     * @return Settings[]
     */
    public function findAllSettingsTypeof()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('settings_types');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->settingsTypeofPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * Return a single blog setting.
     *
     * @param  int $id Identifier of the setting to return.
     * @return Settings
     */
    public function findSettingsTypeof($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('settings_types');
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving SettingsTypeof with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->settingsTypeofPrototype);
        $resultSet->initialize($result);
        $settingsTypeof = $resultSet->current();

        if (! $settingsTypeof) {
            throw new InvalidArgumentException(sprintf(
                'SettingsTypeof with identifier "%s" not found.',
                $id
            ));
        }

        return $settingsTypeof;
    }
}
