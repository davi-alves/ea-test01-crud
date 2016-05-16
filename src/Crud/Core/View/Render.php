<?php
namespace Crud\Core\View;

use Crud\Core\View\Exception\ViewNotFound;

/**
 * Class Render
 * @package Crud\Core\View
 *
 * @author  Davi Alves <hey@davialves.com>
 */
class Render
{
    const VIEW_FILE_EXT = '.phtml';
    /**
     * @var $this
     */
    protected static $_instance;

    /**
     * @var string
     */
    protected $_viewsPath;

    /**
     * @var string
     */
    protected $_templatePath;

    /**
     * Render constructor.
     */
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
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function init($viewsPath)
    {
        return static::instance()
            ->setViewsPath($viewsPath);
    }

    public function setTemplate($path)
    {
        $this->_getViewFile($path);
        $this->_templatePath = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->_templatePath;
    }

    /**
     * Get views path
     *
     * @return string
     */
    public function getViewsPath()
    {
        return $this->_viewsPath;
    }

    /**
     * Set views path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setViewsPath($path)
    {
        $this->_viewsPath = $path;

        return $this;
    }

    /**
     * Render a file from views path
     *
     * @param string     $path
     * @param array|null $params
     *
     * @throws ViewNotFound
     */
    public function render($path, array $params = null)
    {
        // extract view params
        if ($params) {
            extract($params);
        }

        // get rendered view contents
        ob_start();
        include $this->_getViewFile($path);
        $content = ob_get_clean();

        // checks for template file and render within the template if it is set
        if (!$this->getTemplate()) {
            echo $content;
        } else {
            $templateFile = $this->_getViewFile($this->getTemplate());
            $template = file_get_contents($templateFile);
            echo str_replace('{content}', $content, $template);
        }
    }

    /**
     * Get file in views path
     *
     * @param string $path
     *
     * @return string
     * @throws ViewNotFound
     */
    protected function _getViewFile($path)
    {
        $path = explode('/', $path);
        $fileName = array_pop($path) . static::VIEW_FILE_EXT;
        $file = $this->getViewsPath() . DS . join(DS, $path) . DS . $fileName;
        if (!is_file($file)) {
            throw new ViewNotFound();
        }

        return $file;
    }
}
