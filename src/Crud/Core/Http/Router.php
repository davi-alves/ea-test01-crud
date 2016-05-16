<?php

namespace Crud\Core\Http;

/**
 * Class Route
 * @package Crud\Core\Http
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Router
{
    /**
     * @var $this
     */
    protected static $_instance;

    /**
     * @var array
     */
    protected $_routes = ['get' => [], 'post' => []];

    /**
     * @var array
     */
    protected $_data = [];

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
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    /**
     * Helper to add a GET route
     *
     * @param string $name
     * @param string $action
     *
     * @return $this
     * @throws Exception\DuplicatedRoute
     */
    public static function get($name, $action)
    {
        return static::instance()->addRoute('get', $name, $action);
    }

    /**
     * Helper to add a POST route
     *
     * @param string $name
     * @param string $action
     *
     * @return $this
     * @throws Exception\DuplicatedRoute
     */
    public static function post($name, $action)
    {
        return static::instance()->addRoute('post', $name, $action);
    }

    /**
     * Get a route by type and name
     *
     * @param string  $type  get|post
     * @param string  $name  route name
     * @param boolean $match tried to match every route
     *
     * @return string|null
     */
    public function getRoute($type, $name, $match = false)
    {
        if (!$match && isset($this->_routes[$type][$name])) {
            return $this->_routes[$type][$name];
        } elseif ($match) {
            foreach ($this->_routes[$type] as $pattern => $route) {
                $pattern = str_replace('/', '\\/', $pattern);
                $pattern = '/^' . $pattern . '$/i';
                if (preg_match($pattern, $name)) {
                    return $route;
                }
            }
        }
    }

    /**
     * Get route name
     *
     * @param string $type get|post
     * @param string $uri
     *
     * @return int|string
     */
    public function getRouteName($type, $uri)
    {
        foreach ($this->_routes[$type] as $name => $route) {
            $pattern = str_replace('/', '\\/', $name);
            $pattern = '/^' . $pattern . '$/i';
            if (preg_match($pattern, $uri)) {
                return $name;
            }
        }
    }

    /**
     * @param string $type   get|post
     * @param string $name   route name
     * @param string $action 'Controller@method'
     *
     * @return $this
     * @throws Exception\InvalidRoute
     * @throws Exception\DuplicatedRoute
     */
    public function addRoute($type, $name, $action)
    {
        if (strpos($name, '@') === 'false') {
            throw new Exception\InvalidRoute();
        }

        if ($this->getRoute($type, $name)) {
            throw new Exception\DuplicatedRoute("Route by name {$name} already exists");
        }
        $this->_routes[$type][$name] = $action;

        return $this;
    }

    /**
     * Get params for given route name and uri
     *
     * @param string $name route name pattern
     * @param string $uri  current request uri
     *
     * @return mixed
     */
    public function getParamsFromRoute($name, $uri)
    {
        $pattern = str_replace('/', '\\/', $name);
        $pattern = '/^' . $pattern . '$/i';
        preg_match($pattern, $uri, $matches);
        array_shift($matches);

        return $matches;
    }
}
