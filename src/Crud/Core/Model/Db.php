<?php
namespace Crud\Core\Model;

use \PDO;

/**
 * Class Db
 * @package Crud\Core\Model
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Db
{
    /**
     * @var $this
     */
    protected static $_instance;

    /**
     * @var PDO
     */
    protected static $_conn;

    /**
     * @var array
     */
    protected $_config = [];

    protected function __construct()
    {
    }

    /**
     * Get singleton instance
     *
     * @return $this
     */
    public static function instance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static;
        }

        return static::$_instance;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->_config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Get connection pool
     *
     * @return PDO
     */
    public static function getConnection()
    {
        $instance = static::instance();
        if (null === $instance::$_conn) {
            $config = $instance->getConfig();
            static::$_conn = new PDO($config['dns'], $config['username'], $config['password']);
            static::$_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return static::$_conn;
    }
}
