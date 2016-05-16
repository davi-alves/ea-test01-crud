<?php
// require autoload
require_once BASE_PATH . 'vendor' . DS . 'autoload.php';
// get app configuration
$config = require_once BASE_PATH . 'config.php';
// init connection
\Crud\Core\Model\Db::instance()
    ->setConfig($config['database']);
// require routes
require_once 'routes.php';
// init view render
\Crud\Core\View\Render::init($config['views']['path']);
// init request handler
\Crud\Core\Http\Request::init();
