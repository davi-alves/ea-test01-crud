<?php
namespace Crud\Controllers;

use Crud\Core\Controllers\Base;
use Crud\Model\Order;

/**
 * Class Home
 * @package Crud\Controllers
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Home extends Base
{
    public function index()
    {
        $model = new Order();
        $entities = $model->findAll();
        $this->render('home/index', compact('entities'));
    }
}
