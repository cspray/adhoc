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
        $this->_adhocEnsurePropertyHasStore($property);
        $this->_adhocProperties[$property]['get'] = $value;
    }

    public function adhocSetter(string $property, callable $callback) {
        $this->_adhocEnsurePropertyHasStore($property);
        $this->_adhocProperties[$property]['set'] = $callback;
    }

    private function _adhocEnsurePropertyHasStore(string $property) {
        if (!isset($this->_adhocProperties[$property])) {
            $this->_adhocProperties[$property] = [];
        }
    }

    private function _adhocPropertyHasGetter(string $property) {
        return isset($this->_adhocProperties[$property]) && isset($this->_adhocProperties[$property]['get']);
    }

    private function _adhocPropertyHasSetter(string $property) {
        return isset($this->_adhocProperties[$property]) && isset($this->_adhocProperties[$property]['set']);
    }

    public function __get(string $property) {
        if (!$this->_adhocPropertyHasGetter($property)) {
            $msg = 'There is no getter defined for "%s"';
            throw new InvalidPropertyException(sprintf($msg, $property));
        }

        $val = $this->_adhocProperties[$property]['get'];

        return ($val instanceof \Closure) ? $val->call($this) : $val;
    }

    public function __set(string $property, $val) {
        if (!$this->_adhocPropertyHasSetter($property)) {
            $msg = 'There is no setter defined for "%s"';
            throw new InvalidPropertyException(sprintf($msg, $property));
        }

        $this->_adhocProperties[$property]['set']->call($this, $val);
    }

}