<?php
use Crud\Core\Http\Router;

// home
Router::get('/', '\Crud\Controllers\Home@index');
// customers
Router::get('/customers', '\Crud\Controllers\Customers@index');
Router::get('/customers/new', '\Crud\Controllers\Customers@add');
Router::get('/customers/([0-9])', '\Crud\Controllers\Customers@edit');
Router::get('/customers/delete/([0-9])', '\Crud\Controllers\Customers@delete');
Router::post('/customers', '\Crud\Controllers\Customers@save');
// products
Router::get('/products', '\Crud\Controllers\Products@index');
Router::get('/products/new', '\Crud\Controllers\Products@add');
Router::get('/products/([0-9])', '\Crud\Controllers\Products@edit');
Router::get('/products/delete/([0-9])', '\Crud\Controllers\Products@delete');
Router::post('/products', '\Crud\Controllers\Products@save');
// orders
Router::get('/orders', '\Crud\Controllers\Orders@index');
Router::get('/orders/new', '\Crud\Controllers\Orders@add');
Router::get('/orders/delete/([0-9])/([0-9])', '\Crud\Controllers\Orders@delete');
Router::post('/orders', '\Crud\Controllers\Orders@save');
