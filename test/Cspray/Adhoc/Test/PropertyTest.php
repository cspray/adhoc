<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Adhoc\Test;

use Cspray\Adhoc\Exception\InvalidPropertyException;
use Cspray\Adhoc\Test\Stub\PropertyStub;
use PHPUnit_Framework_TestCase as UnitTestCase;

class PropertyTest extends UnitTestCase {

    public function testGettingPropertyWithNoGetterThrowsException() {
        $stub = new PropertyStub();

        $exc = InvalidPropertyException::class;
        $msg = 'There is no getter defined for "foo"';
        $this->setExpectedException($exc, $msg);

        $stub->foo;
    }

    public function nonClosureDataProvider() {
        return [
            [1],
            [true],
            ['count'], // intentionally name of a PHP method
            [new \stdClass()],
            [['1', '2', '3']]
        ];
    }

    /**
     * @dataProvider nonClosureDataProvider
     */
    public function testGetterWithNonClosureValue($val) {
        $stub = new PropertyStub();
        $stub->adhocGetter('fooBar', $val);

        $this->assertSame($stub->fooBar, $val);
    }

    public function testGetterWithClosureValue() {
        $stub = new PropertyStub();
        $stub->adhocGetter('fooBar', function() {
            return 'val';
        });

        $this->assertSame('val', $stub->fooBar);
    }

    public function testGetterHasAccessToDeclaringObject() {
        $stub = new PropertyStub();
        $stub->adhocGetter('fooBar', function() {
            return $this->private;
        });

        $this->assertSame('inside', $stub->fooBar);
    }

    public function testSettingPropertyWithNoSetterThrowsException() {
        $stub = new PropertyStub();

        $exc = InvalidPropertyException::class;
        $msg = 'There is no setter defined for "fooBar"';
        $this->setExpectedException($exc, $msg);

        $stub->fooBar = 'val';
    }

    public function testSettingPropertyCallableGetsValue() {
        $stub = new PropertyStub();

        $passedArg = null;
        $stub->adhocSetter('fooBar', function(string $arg) use(&$passedArg) {
            $passedArg = $arg;
        });

        $stub->fooBar = 'value';

        $this->assertSame($passedArg, 'value');
    }

}