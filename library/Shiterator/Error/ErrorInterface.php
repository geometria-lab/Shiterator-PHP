<?php

namespace Shiterator\Error;

/**
 * Shiterator error interface
 *
 * @package     Shiterator
 * @author      Ivan Shumkov <ivan@shumkov.ru>
 * @copyright   (c) Geometria Lab, 2012
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */
interface ErrorInterface
{
    /**
     * @abstract
     * @return string
     */
    public function getType();

    /**
     * @abstract
     * @return string
     */
    public function getMessage();

    /**
     * @abstract
     * @return string
     */
    public function getFile();

    /**
     * @abstract
     * @return integer
     */
    public function getLine();

    /**
     * @abstract
     * @return array
     */
    public function getStack();

    /**
     * @abstract
     * @param array $stack
     * @return ErrorInterface
     */
    public function setStack(array $stack);

    /**
     * @abstract
     * @return array
     */
    public function toArray();

    /**
     * @abstract
     * @return string
     */
    public function toJson();
}