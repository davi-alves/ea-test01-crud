<?php
namespace Crud\Model;

use Crud\Core\Model\Base;

/**
 * Class Customer
 * @package Crud\Model
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Customer extends Base
{
    protected $_table = 'customer';
    protected $_columns = ['id', 'name', 'email', 'phone'];
    protected $_defaultOrder = 'name ASC';
}
