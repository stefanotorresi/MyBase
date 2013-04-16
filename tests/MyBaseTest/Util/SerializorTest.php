<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\Util;

use PHPUnit_Framework_TestCase;
use stdClass;
use MyBase\Util\Serializor;



class Foo {
    public $name = 'foo';
    public $bar;
}

class Bar {
    public $name = 'bar';
}


class SerializorTest extends PHPUnit_Framework_TestCase
{
    public function testFullAndPartialDepth()
    {

        $foo = new Foo;
        $bar = new Bar;

        $foo->bar = $bar;

        $full = Serializor::toArray($foo);

        $this->assertEquals($foo->name, $full['name']);
        $this->assertEquals($foo->bar->name, $full['bar']['name']);

        $partial = Serializor::toArray($foo, 0);

        $this->assertEquals($foo->name, $partial['name']);
        $this->assertNull($partial['bar']);

    }
}
