<?php

namespace Shiterator;

use Shiterator\Request;

/**
 * Shiterator client class
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
class Client
{
    /**
     * Shiterator server url
     *
     * @var string
     */
    protected $url;

    /**
     * Shiterator server secret
     *
     * @var string
     */
    protected $secret;

    /**
     * Errors
     *
     * @var Error\ErrorInterface[]
     */
    protected $errors = array();

    /**
     * Request
     *
     * @var Request
     */
    protected $request;

    /**
     * Constructor
     *
     * @param string $url     Shiterator server url
     * @param string $secret  Shiterator server secret
     */
    public function __construct($url, $secret)
    {
        $this->url    = $url;
        $this->secret = $secret;

        $this->request = new Request();
    }

    /**
     * Send errors on destruct
     */
    public function __destruct()
    {
        $this->sendErrors();
    }

    /**
     * Add error
     *
     * @param Error\ErrorInterface $error
     * @return Client
     */
    public function addError(Error\ErrorInterface $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Clear errors
     *
     * @return Client
     */
    public function clearError()
    {
        $this->errors = array();

        return $this;
    }

    /**
     * Send errors
     *
     * @return boolean
     */
    public function sendErrors()
    {
        if (empty($this->errors)) {
            return false;
        }

        $errorsData = array();
        foreach($this->getErrors() as $error) {
            $errorsData[] = $error->toArray();
        }

        $this->clearError();

        $body = escapeshellarg(json_encode(array(
            'secret'  => $this->secret,
            'request' => $this->getRequest()->toArray(),
            'errors'  => $errorsData
        )));

        file_put_contents(APPLICATION_PATH . '/../logs/shiterator.log', $body);

        //exec("curl --max-time 10 -d $body {$this->url} &> /dev/null &");

        return true;
    }

    /**
     * Set request
     *
     * @param Request $request
     * @return Client
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return Client
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Client
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
