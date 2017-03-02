<?php
namespace Settings\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class ZendDbSqlCommand implements SettingsCommandInterface
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
     * {@inheritDoc}
     */
    public function insertSetting(Setting $setting)
    {
        $insert = new Insert('settings');
        $insert->values([
            'title' => $setting->getTitle(),
            'text' => $setting->getText(),
            'setting_key' => $setting->getKey(),
            'typeof_id' => $setting->getTypeof_id()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during setting insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Setting(
            $setting->getTitle(),
            $setting->getText(),
            $setting->getKey(),
            $setting->getTypeof_id(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateSetting(Setting $setting)
    {
        if (! $setting->getId()) {
            throw RuntimeException('Cannot update setting; missing identifier');
        }

        $update = new Update('settings');
        $update->set([
                'title' => $setting->getTitle(),
                'text' => $setting->getText(),
                'setting_key' => $setting->getKey(),
                'typeof_id' => $setting->getTypeof_id(),
        ]);
        $update->where(['id = ?' => $setting->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during setting update operation'
            );
        }

        return $setting;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteSetting(Setting $setting)
    {
        if (! $setting->getId()) {
            throw RuntimeException('Cannot update setting; missing identifier');
        }

        $delete = new Delete('settings');
        $delete->where(['id = ?' => $setting->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
