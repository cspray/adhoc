<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Adhoc;

use Cspray\Adhoc\Exception\InvalidPropertyException;

trait Property {

    private $_adhocProperties = [];

    public function adhocGetter(string $property, $value) {
        $this->_adhocProperties[$property . '_getter'] = $value;
    }

    public function adhocSetter(string $property, callable $callback) {
        $this->_adhocProperties[$property . '_setter'] = $callback;
    }

    public function __get(string $property) {
        $key = $property . '_getter';
        if (!isset($this->_adhocProperties[$key])) {
            $msg = 'There is no getter defined for "%s"';
            throw new InvalidPropertyException(sprintf($msg, $property));
        }

        $val = $this->_adhocProperties[$key];

        return ($val instanceof \Closure) ? $val->call($this) : $val;
    }

    public function __set(string $property, $val) {
        $key = $property . '_setter';
        if (!isset($this->_adhocProperties[$key])) {
            $msg = 'There is no setter defined for "%s"';
            throw new InvalidPropertyException(sprintf($msg, $property));
        }

        $this->_adhocProperties[$key]($val);
    }

}