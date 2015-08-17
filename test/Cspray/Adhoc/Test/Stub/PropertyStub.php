<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Adhoc\Test\Stub;

use Cspray\Adhoc;

class PropertyStub {

    private $private = 'inside';

    use Adhoc\Property;

}