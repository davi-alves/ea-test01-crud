<?php
namespace Crud\Core\Http;

class Response
{
    /**
     * @var $this
     */
    protected static $_instance;

    /**
     * @var Request
     */
    protected $_request;

    protected function __construct()
    {
        $this->_request = Request::instance();
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
     * Redirect to a path in current directory
     *
     * @param string $path
     */
    public function redirect($path)
    {
        $host = $this->_request->getParam('server/HTTP_HOST');
        $path = strpos($path, '/') === 0 ?: "/{$path}";
        header("Location: http://{$host}{$path}");
    }
}
