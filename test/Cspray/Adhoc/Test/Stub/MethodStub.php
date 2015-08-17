<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Adhoc\Test\Stub;

use Cspray\Adhoc;

class MethodStub {

    private $foo = 'inside the class';

    use Adhoc\Method;

}