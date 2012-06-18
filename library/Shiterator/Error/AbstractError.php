<?php

namespace Shiterator\Error;

/**
 * Shiterator abstract error class
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
abstract class AbstractError implements ErrorInterface
{
    /**
     * Type
     *
     * @var string
     */
    protected $type;

    /**
     * Message
     *
     * @var string
     */
    protected $message;

    /**
     * File
     *
     * @var string
     */
    protected $file;

    /**
     * Line
     *
     * @var integer
     */
    protected $line;

    /**
     * Stack
     *
     * @var array
     */
    protected $stack = array();

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get line
     *
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Get stack
     *
     * @return array
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Set stack
     *
     * @param array $stack
     * @return AbstractError
     */
    public function setStack(array $stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * Get error as array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'type'    => $this->type,
            'message' => $this->message,
            'file'    => $this->file,
            'line'    => $this->line,
            'stack'   => $this->stack
        );
    }

    /**
     * Get error as string
     *
     * @return string
     */
    public function toJson()
    {
        return json_decode($this->toArray());
    }

    /**
     * Sanitize the backtrace of unneeded junk.
     *
     * @param array $backtrace
     * @return array
     */
    protected function sanitizeBacktrace($backtrace)
    {
        foreach ($backtrace as &$item) {
            if (isset($item['class'])) {
                $item['function'] = $item['class'] . $item['type'] . $item['function'];
                unset($item['class'], $item['type']);
            }
            if (isset($item['object'])) {
                unset($item['object']);
            }
            unset($item['args']);
        }

        return $backtrace;
    }
}