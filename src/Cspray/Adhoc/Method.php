<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Adhoc;

use Cspray\Adhoc\Exception\InvalidMethodException;

trait Method {

    private $_adhocMethods = [];

    public function adhocMethod(string $name, $val) {
        $this->_adhocMethods[$name] = $val;
    }

    public function __call(string $method, array $args) {
        if (!isset($this->_adhocMethods[$method])) {
            $msg = 'Could not find a callback for method "%s"';
            throw new InvalidMethodException(sprintf($msg, $method));
        }

        $val = $this->_adhocMethods[$method];

        return ($val instanceof \Closure) ? $val->call($this, ...$args) : $val;
    }

}