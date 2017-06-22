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

class ZendDbSqlRepository implements SettingsRepositoryInterface
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
    private $settingPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Setting $settingPrototype
    ) {
        $this->db               = $db;
        $this->hydrator         = $hydrator;
        $this->settingPrototype = $settingPrototype;
    }

    /**
     * Return a set of all blog settings that we can iterate over.
     *
     * Each entry should be a Settings instance.
     *
     * @return Settings[]
     */
    public function findAllSettings($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }
        $sql       = new Sql($this->db);
        $select    = $sql->select('settings');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->settingPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }

    private function fetchPaginatedResults()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('settings')
            ->join(['st' => 'settings_types'], 'settings.typeof_id = st.id',
                [
                    'settings_types.id'          => 'id',
                    'settings_types.title'       => 'title',
                    'settings_types.typeof'      => 'typeof'
                ],
                Select::JOIN_LEFT);
        $resultSetPrototype = new HydratingResultSet($this->hydrator, $this->settingPrototype);
        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            // our configured select object:
            $select,
            // the adapter to run it against:
            $this->db,
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    /**
     * Return a single blog setting.
     *
     * @param  int $id Identifier of the setting to return.
     * @return Settings
     */
    public function findSetting($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('settings')
            ->join(['st' => 'settings_types'], 'settings.typeof_id = st.id',
                [
                    'settings_types.id'          => 'id',
                    'settings_types.title'       => 'title',
                    'settings_types.typeof'      => 'typeof'
                ],
                Select::JOIN_LEFT);
        $select->where(['settings.id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving setting with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->settingPrototype);
        $resultSet->initialize($result);
        $setting = $resultSet->current();

        if (! $setting) {
            throw new InvalidArgumentException(sprintf(
                'Setting with identifier "%s" not found.',
                $id
            ));
        }

        return $setting;
    }
    
    /**
     * 
     * @param string
     * @return Setting
     * 
     */
    public function findSettingByKey($key)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('settings')
            ->join(['st' => 'settings_types'], 'settings.typeof_id = st.id',
                [
                    'settings_types.id'          => 'id',
                    'settings_types.title'       => 'title',
                    'settings_types.typeof'      => 'typeof'
                ],
                Select::JOIN_LEFT);
        $select->where(['settings.setting_key = ?' => $key]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving setting with identifier "%s"; unknown database error.',
                $key
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->settingPrototype);
        $resultSet->initialize($result);
        $setting = $resultSet->current();

        if (! $setting) {
            throw new InvalidArgumentException(sprintf(
                'Setting with identifier "%s" not found.',
                $key
            ));
        }

        return $setting;
    }
    
    public function getDbAdapter () {
        return $this->db;
    }
}
