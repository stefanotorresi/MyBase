<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\Util;

use PHPUnit_Framework_TestCase;
use MyBase\Util\Serializor;

class SerializorTest extends PHPUnit_Framework_TestCase
{
    public function testFullAndPartialDepth()
    {
        $foo = new TestAsset\Foo;
        $bar = new TestAsset\Bar;

        $foo->bar = $bar;

        $full = Serializor::toArray($foo);

        $this->assertEquals($foo->name, $full['name']);
        $this->assertEquals($foo->bar->name, $full['bar']['name']);

        $partial = Serializor::toArray($foo, 0);

        $this->assertEquals($foo->name, $partial['name']);
        $this->assertNull($partial['bar']);

    }
}
