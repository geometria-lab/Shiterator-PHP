<?php

namespace Shiterator;

require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Error/ErrorInterface.php';
require_once __DIR__ . '/Error/AbstractError.php';
require_once __DIR__ . '/Error/Fatal.php';
require_once __DIR__ . '/Error/Regular.php';
require_once __DIR__ . '/Error/Exception.php';
require_once __DIR__ . '/Client.php';

use Shiterator\Exception;

/**
 * Shiterator error handler singleton class
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
class ErrorHandler
{
    /**
     * Instance
     *
     * @var ErrorHandler
     */
    static protected $instance;

    /**
     * Regular error types
     *
     * @var array
     */
    static protected $errorTypes = array(
        E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parse',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'CoreError',
        E_CORE_WARNING      => 'CoreWarning',
        E_COMPILE_ERROR     => 'CompileError',
        E_COMPILE_WARNING   => 'CompileWarning',
        E_USER_ERROR        => 'UserError',
        E_USER_WARNING      => 'UserWarning',
        E_USER_NOTICE       => 'UserNotice',
        E_STRICT            => 'Strict',
        E_RECOVERABLE_ERROR => 'RecoverableError',
    );

    /**
     * Client
     *
     * @var Client
     */
    protected $client;

    /**
     * Callback called when error caught
     *
     * @var callback
     */
    protected $callback;

    /**
     * Is error handlers set
     *
     * @var bool
     */
    protected $isHandlerSet = false;

    /**
     * Set error handler
     *
     * @static
     * @param $url
     * @param $secret
     * @return ErrorHandler
     */
    static public function set($url, $secret)
    {
        $client = new Client($url, $secret);

        return static::getInstance()->setClient($client)
                                    ->setHandler();
    }

    /**
     * Get error handler
     *
     * @static
     * @return ErrorHandler
     */
    static public function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set error handler
     *
     * @return ErrorHandler
     * @throws Exception
     */
    public function setHandler()
    {
        if ($this->getClient() === null) {
            throw new Exception('Client not preset, use setClient() method');
        }

        if ($this->isHandlerSet()) {
            throw new Exception('Error handlers already set');
        }

        set_error_handler(array($this, 'handleRegular'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleShutdown'));

        return $this;
    }

    /**
     * Is handlers set
     *
     * @return bool
     */
    public function isHandlerSet()
    {
        return $this->isHandlerSet;
    }

    /**
     * Restore prev error handlers
     *
     * @return ErrorHandler
     * @throws Exception
     */
    public function restoreHandler()
    {
        if (!$this->isHandlerSet()) {
            throw new Exception('Error handlers not set');
        }

        restore_error_handler();
        restore_exception_handler();

        return $this;
    }

    /**
     * Set client
     *
     * @param Client $client
     * @return ErrorHandler
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get Client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set callback
     *
     * @param callback|null $callback
     * @throws Exception
     * @return ErrorHandler
     */
    public function setCallback($callback)
    {
        if ($callback !== null && !is_callable($callback)) {
            throw new Exception('Shiterator callback is not callable');
        }

        $this->callback = $callback;

        return $this;
    }

    /**
     * Get callback
     *
     * @return callback
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Handle regular php error
     *
     * @param integer $errorNumber
     * @param string $message
     * @param string $file
     * @param integer $line
     * @return boolean
     */
    public function handleRegular($errorNumber, $message, $file, $line)
    {
        $errorReporting = error_reporting();

        if ($errorReporting === 0 || ($errorReporting & $errorNumber) === 0 || !isset(static::$errorTypes[$errorNumber])) {
            return true;
        }

        $error = $this->createRegularError($errorNumber, $message, $file, $line);

        $this->addError($error);

        return false;
    }

    /**
     * Handle exception
     *
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $error = new Error\Exception($exception);

        $this->addError($error);

        throw $exception;
    }

    /**
     * Handle shutdown
     */
    public function handleShutdown()
    {
        if (static::$instance === null) {
            return;
        }

        $lastError = error_get_last();

        if ($lastError) {
            $error = $this->createRegularError($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
            if ($error instanceof Error\Fatal) {
                $this->addError($error);
                $this->getClient()->sendErrors();
            }
        }

        static::$instance = null;
    }

    /**
     * Add error to client if callback do not return false
     *
     * @param Error\ErrorInterface $error
     * @return bool
     */
    protected function addError(Error\ErrorInterface $error)
    {
        if ($this->callback && call_user_func($this->callback, $error) === false) {
            return false;
        }

        $this->getClient()->addError($error);

        return true;
    }

    /**
     * Create regular error
     *
     * @param $errorNumber
     * @param $message
     * @param $file
     * @param $line
     * @return Error\ErrorInterface
     */
    protected function createRegularError($errorNumber, $message, $file, $line)
    {
        $className = 'Shiterator\\Error\\' . static::$errorTypes[$errorNumber];

        return new $className($message, $file, $line);
    }
}
