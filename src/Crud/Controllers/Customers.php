<?php
namespace Crud\Controllers;

use Crud\Core\Controllers\Base;
use Crud\Core\Model\Exception\NotFound;
use Crud\Model\Customer;

class Customers extends Base
{
    public function index()
    {
        $model = new Customer();
        $entities = $model->findAll();
        $this->render('customers/index', compact('entities'));
    }

    public function add()
    {
        $this->render('customers/form');
    }

    public function edit($id)
    {
        $model = new Customer();
        $entity = $model->find($id);
        if (!$entity) {
            throw new NotFound("Customer by id {$id} not found");
        }
        $this->render('customers/form', compact('entity'));
    }

    public function delete($id)
    {
        $model = new Customer();
        $entity = $model->find($id);
        if (!$entity) {
            throw new NotFound("Customer by id {$id} not found");
        }
        $entity->delete();
        $this->getResponse()->redirect('customers');
    }

    public function save()
    {
        $entity = new Customer();
        $entity->loadData($this->getRequest()->getPost());
        $entity->save();
        $this->getResponse()->redirect('customers');
    }
}
