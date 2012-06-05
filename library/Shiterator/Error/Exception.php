<?php

namespace Shiterator\Error;

/**
 * Shiterator error exception class
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
class Exception extends AbstractError implements Fatal
{
    /**
     * Exception
     *
     * @var \Exception
     */
    protected $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;

        $this->type    = get_class($exception);
        $this->file    = $exception->getFile();
        $this->line    = $exception->getLine();
        $this->message = "{$this->type}: {$exception->getMessage()} on {$this->file}:{$this->line}";
        $this->stack   = $this->sanitizeBacktrace($exception->getTrace());
    }

    public function getException()
    {
        return $this->exception;
    }
}