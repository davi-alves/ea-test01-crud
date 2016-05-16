<?php
namespace Crud\Core\Model;

use Crud\Core\Traits\GetSet;

/**
 * Class Base
 * @package Crud\Core\Model
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Base
{
    use GetSet;

    /**
     * Table name
     *
     * @var string
     */
    protected $_table;

    /**
     * Available columns
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Default sort order
     *
     * @var
     */
    protected $_defaultOrder;

    /**
     * Model data
     *
     * @var array
     */
    private $_data = [];

    /**
     * @var \PDO
     */
    private $_conn;

    public function __construct()
    {
        $this->_conn = Db::getConnection();
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->_conn;
    }

    /**
     * Find by id
     *
     * @param integer $id
     *
     * @return bool|Base
     */
    public function find($id)
    {
        $query = $this->getConnection()->prepare($this->_getSelectQuery());
        $query->execute(array_combine($this->_getSelectQueryParams(), func_get_args()));
        if (!$query->rowCount()) {
            return false;
        }

        return $this->loadData($query->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * Find all items
     *
     * @return array
     */
    public function findAll()
    {
        $order = $this->_defaultOrder ? " ORDER BY {$this->_defaultOrder}" : null;
        $query = $this->getConnection()->query("SELECT * FROM `{$this->_table}`{$order}");
        $query->execute();

        $results = [];
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $item = new static;
            $item->loadData($row);
            $results[] = $item;
        }

        return $results;
    }

    /**
     * Save the current model data
     *
     * @return $this
     */
    public function save()
    {
        $this->_beforeSave();
        $data = $this->_getColumns();
        if ($this->id) {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            $this->_update($data);
        } else {
            $id = $this->_insert($data);
            $this->id = $id;
        }
        $this->_afterSave();

        return $this;
    }

    /**
     * Deletes the current model
     */
    public function delete()
    {
        $conn = $this->getConnection();
        $query = $conn->prepare($this->_getDeleteQuery());
        $query->execute($this->_getDeleteQueryParams());
    }

    /**
     * Load data to model from array
     *
     * @param array $data
     *
     * @return $this
     */
    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Runs before saving
     */
    protected function _beforeSave()
    {

    }

    /**
     * Runs before saving
     */
    protected function _afterSave()
    {

    }

    /**
     * @return string
     */
    protected function _getSelectQuery()
    {
        return "SELECT * FROM `{$this->_table}` WHERE id = :id LIMIT 1";
    }

    /**
     * @return array
     */
    protected function _getSelectQueryParams()
    {
        return [':id'];
    }

    /**
     * @return string
     */
    protected function _getDeleteQuery()
    {
        return "DELETE FROM `{$this->_table}` WHERE id = :id";
    }

    /**
     * @return array
     */
    protected function _getDeleteQueryParams()
    {
        return [':id' => $this->id];
    }

    /**
     * Make an insert query
     *
     * @param array $data
     *
     * @return int
     */
    private function _insert(array $data)
    {
        $dataKeys = array_keys($data);
        $columns = implode(', ', $dataKeys);

        $values = [];
        foreach ($data as $key => $value) {
            $values[":$key"] = htmlentities($value);
        }
        $keys = implode(', ', array_keys($values));

        $conn = $this->getConnection();
        $query = $conn->prepare("INSERT INTO `{$this->_table}` ({$columns}) VALUES ({$keys})");
        $query->execute($values);

        return (int)$conn->lastInsertId();
    }

    /**
     * Make an update query
     *
     * @param array $data
     */
    private function _update(array $data)
    {
        $pairs = [];
        $values = [':id' => $this->id];
        foreach ($data as $key => $value) {
            $pairs[] = "{$key} = :{$key}";
            $values[":$key"] = htmlentities($value);
        }
        $pairs = join(', ', $pairs);

        $conn = $this->getConnection();
        $query = $conn->prepare("UPDATE `{$this->_table}` SET {$pairs} WHERE id = :id");
        $query->execute($values);
    }

    /**
     * Get only the fields that are table columns
     *
     * @return array
     */
    private function _getColumns()
    {
        if (!$this->_columns) {
            return $this->_data;
        }

        return array_intersect_key($this->_data, array_flip($this->_columns));
    }
}
