<?php
namespace Universe\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class StarTypeCommand implements EntityCommandInterface
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
        $statement = $this->db->query('TRUNCATE TABLE stars_types');
        $statement->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function insertEntity(Entity $star_type)
    {
        $insert = new Insert($star_type->GetTable());
        $insert->values([
            'name'          => $star_type->getName(),
            'description'   => $star_type->getDescription(),
            'color_eng'     => $star_type->get_color_eng(),
            'color_rus'     => $star_type->get_color_rus(),
            'kelvin_min'    => $star_type->get_kelvin_min(),
            'kelvin_max'    => $star_type->get_kelvin_max(),
            'star_class'    => $star_type->get_star_class(),
            'part'          => $star_type->get_part()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during star_type insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new StarType(
            $star_type->getName(),
            $star_type->getDescription(),
            $star_type->get_color_eng(),
            $star_type->get_color_rus(),
            $star_type->get_kelvin_min(),
            $star_type->get_kelvin_max(),
            $star_type->get_star_class(),
            $star_type->get_part(),
            $result->getGeneratedValue()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntity(Entity $star_type)
    {
        if (! $star_type->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }
        
        $update = new Update($star_type->GetTable());
        $update->set([
                'name'          => $star_type->getName(),
                'description'   => $star_type->getDescription(),
                'color_eng'     => $star_type->get_color_eng(),
                'color_rus'     => $star_type->get_color_rus(),
                'kelvin_min'    => $star_type->get_kelvin_min(),
                'kelvin_max'    => $star_type->get_kelvin_max(),
                'star_class'    => $star_type->get_star_class(),
                'part'          => $star_type->get_part(),
        ]);
        $update->where(['id = ?' => $star_type->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during star_type update operation'
            );
        }

        return $star_type;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEntity(Entity $star_type)
    {
        if (! $star_type->getId()) {
            throw RuntimeException('Cannot update entity; missing identifier');
        }

        $delete = new Delete($star_type->getTable());
        $delete->where(['id = ?' => $star_type->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }
}
