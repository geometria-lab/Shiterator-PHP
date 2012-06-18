<?php

namespace Shiterator\Error;

/**
 * Shiterator regular php error class
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
abstract class Regular extends AbstractError
{
    public function __construct($message, $file, $line)
    {
        $this->type = static::$title;
        $this->file    = $file;
        $this->line    = $line;
        $this->message = "{$this->type}: {$message} on {$this->file}:{$this->line}";

        $stack = debug_backtrace();
        array_shift($stack);

        $this->stack = $this->sanitizeBacktrace($stack);
    }
}

class Error extends Regular implements Fatal
{
    protected static $title = 'Fatal error';
}

class Warning extends Regular
{
    protected static $title = 'Warning';
}

class Parse extends Regular implements Fatal
{
    protected static $title = 'Parse error';
}

class Notice extends Regular
{
    protected static $title = 'Notice';
}

class CoreError extends Regular implements Fatal
{
    protected static $title = 'Core (startup) fatal error';
}

class CoreWarning extends Regular
{
    protected static $title = 'Core (startup) warning';
}

class CompileError extends Regular implements Fatal
{
    protected static $title = 'Compile-time fatal error';
}

class CompileWarning extends Regular
{
    protected static $title = 'Compile-time warning';
}

class UserError extends Regular implements Fatal
{
    protected static $title = 'User error';
}

class UserWarning extends Regular
{
    protected static $title = 'User warning';
}

class UserNotice extends Regular
{
    protected static $title = 'User notice';
}

class Strict extends Regular
{
    protected static $title = 'Strict notice';
}

class RecoverableError extends Regular implements Fatal
{
    protected static $title = 'Catchable fatal error';
}