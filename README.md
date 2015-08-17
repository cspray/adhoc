# Adhoc

A PHP library to setup adhoc properties and methods to a class at runtime.

## Installation

We recommend you use Composer to install Adhoc.

```
composer require cspray/adhoc:0.1.0
```

## User Guide

Sometimes the best solution to a problem is setting a property or method on a class at runtime. In PHP this functionality 
is provided by [magic methods](http://php.net/manual/en/language.oop5.overloading.php). Adhoc provides a bit of sugar to 
make this magic a little easier to work with.

### Adhoc Properties

If you just need to get a static value...

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {
    
    use Cspray\Adhoc\Property;
    
}

$foo = new Foo;
$foo->adhocGetter('foo', 'bar');

echo $foo->foo; // 'bar'
```

Or maybe some kind of callback is more up your alley...

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {

    use Cspray\Adhoc\Property;

    private $bar = 'bar';

}

$foo = new Foo;
$foo->adhocGetter('foo', function() {
    return 'foo' . $this->bar;
});

echo $foo->foo; // 'foobar'
```

We'll only invoke a passed value if it is a `Closure`, even if the value could otherwise be invoked.

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {
    
    use Cspray\Adhoc\Property;
    
}

$foo = new Foo;
$foo->adhocGetter('foo', 'count');

echo $foo->foo; // 'count'
```

Would be silly to support 'getter' and not 'setter'.

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {

    use Cspray\Adhoc\Property;

}

$state = new stdClass;
$foo = new Foo;
$foo->adhocGetter('foo', function() use($state) {
    return $state->val ?? 'nope';
});

$foo->adhocSetter('foo', function($val) use($state) {
    $state->val = $val;
});

echo $foo->foo; // 'nope'
$foo->foo = 'yep';
echo $foo->foo; // 'yep'
```

### Adhoc Methods

Simple method just returning a static value...

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {

    use Cspray\Adhoc\Method;

}

$foo = new Foo;
$foo->adhocMethod('foo', 'bar');

echo $foo->foo(); // 'bar'
```

And of course you can pass in a Closure...

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {
    
    use Cspray\Adhoc\Method;
    
}

$foo = new Foo;
$foo->adhocMethod('foo', function() {
    return 'bar';
});

echo $foo->foo(); // 'bar'
```

Arguments are supported, naturally.

```php linenos
<?php

require_once __DIR__ . '/vendor/autoload.php';

class Foo {

    use Cspray\Adhoc\Method;

}

$foo = new Foo;
$foo->adhocMethod('foo', function($one, $two) {
    return $one . $two;
});

echo $foo->foo('foo', 'bar'); // 'foobar'
```