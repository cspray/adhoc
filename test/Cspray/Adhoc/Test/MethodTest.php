<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Adhoc\Test;

use Cspray\Adhoc\Exception\InvalidMethodException;
use Cspray\Adhoc\Test\Stub\MethodStub;
use PHPUnit_Framework_TestCase as UnitTestCase;

class MethodTest extends UnitTestCase {

    public function testCallingMethodNotPresentThrowsException() {
        $stub = new MethodStub();

        $exc = InvalidMethodException::class;
        $msg = 'Could not find a callback for method "fooBar"';
        $this->setExpectedException($exc, $msg);

        $stub->fooBar();
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
    public function testCallingMethodReturnsNonClosureValue($val) {
        $stub = new MethodStub();
        $stub->adhocMethod('fooBar', $val);

        $this->assertSame($val, $stub->fooBar());
    }

    public function testCallingMethodWithCallbackValue() {
        $stub = new MethodStub();
        $stub->adhocMethod('fooBar', function() {
            return 'oh yea';
        });

        $this->assertSame('oh yea', $stub->fooBar());
    }

    public function testClosureGetsArguments() {
        $stub = new MethodStub();
        $stub->adhocMethod('fooBar', function($one, $two, $three) {
            return [$one, $two, $three];
        });

        $this->assertSame([1,2,3], $stub->fooBar(1,2,3));
    }

    public function testClosureGetsAccessToDeclaringClass() {
        $stub = new MethodStub();
        $stub->adhocMethod('fooBar', function() {
            return $this->foo;
        });

        $this->assertSame('inside the class', $stub->fooBar());
    }

}