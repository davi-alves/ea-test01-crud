<?php
namespace Crud\Controllers;

use Crud\Core\Controllers\Base;
use Crud\Core\Model\Exception\NotFound;
use Crud\Model\Customer;
use Crud\Model\Order;
use Crud\Model\Product;

class Orders extends Base
{
    public function index()
    {
        $model = new Order();
        $entities = $model->findAll();
        $this->render('orders/index', compact('entities'));
    }

    public function add()
    {
        $customer = new Customer();
        $product = new Product();
        $this->render('orders/form', ['customers' => $customer->findAll(), 'products' => $product->findAll()]);
    }

    public function delete($customerId, $productId)
    {
        $model = new Order();
        $entity = $model->find($customerId, $productId);
        if (!$entity) {
            throw new NotFound('Order by not found');
        }
        $entity->delete();
        $this->getResponse()->redirect('orders');
    }

    public function save()
    {
        $entity = new Order();
        $entity->loadData($this->getRequest()->getPost());
        $entity->save();
        $this->getResponse()->redirect('orders');
    }
}
