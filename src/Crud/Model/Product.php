<?php
namespace Crud\Model;

use Crud\Core\Model\Base;

/**
 * Class Product
 * @package Crud\Model
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Product extends Base
{
    protected $_table = 'product';
    protected $_columns = ['id', 'name', 'description', 'price'];
    protected $_defaultOrder = 'name ASC';

    protected function _beforeSave()
    {
        // format price
        if (false !== strpos($this->price, ',')) {
            $this->price = (float)strtr($this->price, ['.' => '', ',' => '.']);
        }
    }
}
