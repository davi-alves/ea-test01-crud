<?php
namespace Crud\Core\Controllers;

use Crud\Core\Http\Request;
use Crud\Core\Http\Response;
use Crud\Core\Http\Router;
use Crud\Core\View\Render;

/**
 * Class Base
 * @package Crud\Core\Controllers
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Base
{
    /**
     * @var Router
     */
    private $_router;

    /**
     * @var Render
     */
    private $_viewRender;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    /**
     * Template path
     *
     * @var string
     */
    protected $_template = 'template/default';

    public function __construct()
    {
        // get request
        $this->_request = Request::instance();
        // get response
        $this->_response = Response::instance();
        // get router
        $this->_router = Router::instance();
        // get view render
        $this->_viewRender = Render::instance();
        // set template
        if ($this->_template) {
            $this->_viewRender->setTemplate($this->_template);
        }
    }

    /**
     * Render a view
     *
     * @param string     $path
     * @param array|null $params
     */
    protected function render($path, array $params = null)
    {
        $this->_viewRender->render($path, $params);
    }

    /**
     * Get request object
     *
     * @return Request
     */
    protected function getRequest()
    {
        return $this->_request;
    }

    /**
     * Get response object
     *
     * @return Response
     */
    protected function getResponse()
    {
        return $this->_response;
    }

    /**
     * Get a param in current request method
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function getParam($name)
    {
        $request = $this->getRequest();
        $method = $request->getMethod();

        return $request->getParam(strtolower($method) . '/' . $name);
    }
}
