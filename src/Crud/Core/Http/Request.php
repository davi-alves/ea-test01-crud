<?php

namespace Crud\Core\Http;

/**
 * Class Request
 * @package Crud\Core\Http
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    /**
     * @var $this
     */
    protected static $_instance;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @var Router
     */
    protected $_router;

    protected function __construct()
    {

    }

    /**
     * Get singleton instance
     *
     * @return $this
     */
    public static function instance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static;
        }

        return static::$_instance;
    }

    /**
     * Init the request handler
     */
    public static function init()
    {
        $instance = static::instance();

        return $instance
            ->setRouter(Router::instance())
            ->_handle();
    }

    /**
     * Set router
     *
     * @param Router $router
     *
     * @return $this
     */
    public function setRouter(Router $router)
    {
        $this->_router = $router;

        return $this;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->_router;
    }

    /**
     * Return request method type
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->getParam('server/REQUEST_METHOD');
    }

    /**
     * Get request URI
     *
     * @return string
     */
    public function getRequestURI()
    {
        return $this->getParam('server/REQUEST_URI');
    }

    /**
     * @return bool
     */
    public function isGetRequest()
    {
        return $this->getMethod() === static::METHOD_GET;
    }

    /**
     * @return bool
     */
    public function isPostRequest()
    {
        return $this->getMethod() === static::METHOD_POST;
    }

    /**
     * Get post request
     *
     * @return mixed
     */
    public function getPost()
    {
        return $this->getParam('post');
    }

    /**
     * get a value from request data
     *
     * @param string     $path value
     * @param array|null $val
     *
     * @return mixed
     */
    public function getParam($path, array $val = null)
    {
        $parts = explode('/', $path);
        if (count($parts) == 1) {
            $part = $parts[0];
            if ($val && isset($val[$part])) {
                return $val[$part];
            } elseif (isset($this->_data[$part])) {
                return $this->_data[$part];
            }

            return false;
        } else {
            $part = array_shift($parts);
            $path = join('/', $parts);
            if ($val && !isset($val[$part])) {
                return false;
            }

            return $this->getParam($path, $val ? $val[$part] : $this->_data[$part]);
        }
    }

    /**
     * Handle the overall request
     *
     * @return $this
     * @throws Exception\InvalidRequest
     */
    protected function _handle()
    {
        return $this
            ->_addData('post', $_POST)
            ->_addData('get', $_GET)
            ->_addData('server', $_SERVER)
            ->_handleRequestRoute();
    }

    /**
     * Handle request route
     *
     * @return $this
     * @throws Exception\InvalidRequest
     * @throws Exception\RouteNotFound
     */
    protected function _handleRequestRoute()
    {
        $method = strtolower($this->getMethod());
        if (
            !in_array($this->getMethod(), [static::METHOD_GET, static::METHOD_POST])
            || !isset($this->_data[$method])
        ) {
            throw new Exception\InvalidRequest();
        }

        // get the request URI
        $requestURI = $this->getRequestURI();
        // get the route
        $router = $this->getRouter();
        $name = $router->getRouteName($method, $requestURI);
        $route = $router->getRoute($method, $requestURI, true);
        if (!$route || !$name) {
            throw new Exception\RouteNotFound();
        }
        // get the controller and action
        list($controller, $action) = explode('@', $route);
        $instance = new $controller;
        call_user_func_array(
            [$instance, $action],
            $router->getParamsFromRoute($name, $requestURI)
        );

        return $this;
    }

    /**
     * Store given data to request class
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    private function _addData($key, $value)
    {
        $this->_data = array_merge(
            $this->_data,
            [
                $key => array_merge(
                    isset($this->_data['get']) ? $this->_data['get'] : [],
                    $value
                )
            ]
        );

        return $this;
    }
}
