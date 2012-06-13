<?php

namespace Shiterator;

/**
 * Shiterator request class
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
class Request
{
    /**
     * Project root path
     *
     * @var string
     */
    protected $projectRoot;

    /**
     * Controller
     *
     * @var string
     */
    protected $controller;

    /**
     * Action
     *
     * @var string
     */
    protected $action;

    /**
     * Host
     *
     * @var string
     */
    protected $host;

    /**
     * Uri
     *
     * @var string
     */
    protected $uri;

    /**
     * Method
     *
     * @var string
     */
    protected $method;

    /**
     * User agent
     *
     * @var string
     */
    protected $userAgent;

    /**
     * POST params
     *
     * @var array
     */
    protected $postParams = array();

    /**
     * GET params
     *
     * @var array
     */
    protected $getParams = array();

    /**
     * Session data
     *
     * @var array
     */
    protected $sessionData = array();

    /**
     * User defined data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor. Populate request data.
     */
    public function __construct()
    {
        $this->setParams($_GET)
             ->setPostParams($_POST);

        if (isset($_SESSION)) {
            $this->setSessionData((array)$_SESSION);
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $this->setHost($_SERVER['HTTP_HOST']);
        }
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->setUri($_SERVER['REQUEST_URI']);
        }
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->setMethod($_SERVER['REQUEST_METHOD']);
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        }

        $this->setData($_SERVER);
        $this->setToData('SAPI', PHP_SAPI);
    }

    /**
     * Get GET params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->getParams;
    }

    /**
     * Set GET params
     *
     * @param array $params
     * @return Request
     */
    public function setParams(array $params)
    {
        $this->getParams = $params;

        return $this;
    }

    /**
     * Get POST params
     *
     * @return array
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     * Set POST params
     *
     * @param array $data
     * @return Request
     */
    public function setPostParams(array $data)
    {
        $this->postParams = $data;

        return $this;
    }

    /**
     * Get session data
     *
     * @return array
     */
    public function getSessionData()
    {
        return $this->sessionData;
    }

    /**
     * Set session data
     *
     * @param array $data
     * @return Request
     */
    public function setSessionData(array $data)
    {
        $this->sessionData = $data;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set host
     *
     * @param $host
     * @return Request
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set uri
     *
     * @param $uri
     * @return Request
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get user defined data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set user defined data
     *
     * @param array $data
     * @return Request
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get element from user defined data
     *
     * @param $name
     * @return mixed
     */
    public function getFromData($name)
    {
        return $this->data[$name];
    }

    /**
     * Set element to user defined data
     *
     * @param $name
     * @param $value
     * @return Request
     */
    public function setToData($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * Set project root path
     *
     * @param $projectRoot
     * @return Request
     */
    public function setProjectRoot($projectRoot)
    {
        $this->projectRoot = $projectRoot;

        return $this;
    }

    /**
     * Get project root path
     *
     * @return string
     */
    public function getProjectRoot()
    {
        return $this->projectRoot;
    }

    /**
     * Set method
     *
     * @param $method
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set User Agent
     *
     * @param $userAgent
     * @return Request
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get User Agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set action
     *
     * @param $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set controller
     *
     * @param $controller
     * @return Request
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get request as array
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach(get_object_vars($this) as $name => $value) {
            if ($value !== null) {
                $array[$name] = $value;
            }
        }

        return $array;
    }
}
