<?php
namespace Crud\Model;

use Crud\Core\Model\Base;

class Order extends Base
{
    protected $_table = 'order';
    protected $_defaultOrder = 'customer_id ASC';

    /**
     * @return bool|Customer
     */
    public function getCustomer()
    {
        if (null === $this->customer) {
            $model = new Customer();
            $this->customer = $model->find($this->customer_id);
        }

        return $this->customer;
    }

    /**
     * @return bool|Product
     */
    public function getProduct()
    {
        if (null === $this->product) {
            $model = new Product();
            $this->product = $model->find($this->product_id);
        }

        return $this->product;
    }

    /**
     * @inheritdoc
     */
    protected function _getSelectQuery()
    {
        return "SELECT * FROM `{$this->_table}` WHERE customer_id = :customer_id AND product_id = :product_id LIMIT 1";
    }

    /**
     * @inheritdoc
     */
    protected function _getSelectQueryParams()
    {
        return [':customer_id', ':product_id'];
    }

    /**
     * @inheritdoc
     */
    protected function _getDeleteQuery()
    {
        return "DELETE FROM `{$this->_table}` WHERE customer_id = :customer_id AND product_id = :product_id";
    }

    /**
     * @inheritdoc
     */
    protected function _getDeleteQueryParams()
    {
        return [':product_id' => $this->product_id, ':customer_id' => $this->customer_id];
    }
}
