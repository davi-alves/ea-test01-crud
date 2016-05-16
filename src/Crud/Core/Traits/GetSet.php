<?php
namespace Crud\Core\Traits;

/**
 * Class GetSet
 * @package Crud\Code\Traits
 *
 * @author  Davi Alves <hey@davialves.com>
 */
trait GetSet
{
    /**
     * Helper to access database data directly from model class
     *
     * @param string $name
     *
     * @return null
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->_data)) {
            return null;
        }

        return $this->_data[$name];
    }

    /**
     * Helper to set data directly to model class without polluting the class
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return null
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }
}
