<?php
namespace Crud\Controllers;

use Crud\Core\Controllers\Base;
use Crud\Core\Model\Exception\NotFound;
use Crud\Model\Product;

class Products extends Base
{
    public function index()
    {
        $model = new Product();
        $entities = $model->findAll();
        $this->render('products/index', compact('entities'));
    }

    public function add()
    {
        $this->render('products/form');
    }

    public function edit($id)
    {
        $model = new Product();
        $entity = $model->find($id);
        if (!$entity) {
            throw new NotFound("Product by id {$id} not found");
        }
        $this->render('products/form', compact('entity'));
    }

    public function delete($id)
    {
        $model = new Product();
        $entity = $model->find($id);
        if (!$entity) {
            throw new NotFound("Product by id {$id} not found");
        }
        $entity->delete();
        $this->getResponse()->redirect('products');
    }

    public function save()
    {
        $entity = new Product();
        $entity->loadData($this->getRequest()->getPost());
        $entity->save();
        $this->getResponse()->redirect('products');
    }
}
