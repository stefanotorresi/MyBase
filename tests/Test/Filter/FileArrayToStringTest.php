<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\Filter;

use MyBase\Filter\FileArrayToString;
use PHPUnit_Framework_TestCase;

class FileArrayToStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider valuesProvider
     */
    public function testFilter($value, $options, $expectedResult)
    {
        $filter = new FileArrayToString($options);
        $this->assertSame($expectedResult, $filter->filter($value));
    }

    public function valuesProvider()
    {
        return [
            [
                ['name' => 'asd', 'tmp_name' => '/tmp/asdasd', 'size' => 0, 'type'=> '', 'error' => UPLOAD_ERR_OK],
                [],
                '/tmp/asdasd'
            ],
            [
                ['name' => 'asd', 'tmp_name' => '/tmp/asdasd', 'size' => 0, 'type'=> '', 'error' => UPLOAD_ERR_OK],
                ['use_uploaded_name' => true],
                'asd'
            ],
            [
                ['name' => 'asd', 'tmp_name' => '/tmp/asdasd', 'size' => 0, 'type'=> '', 'error' => UPLOAD_ERR_OK],
                ['basename' => true],
                'asdasd'
            ],
            [
                ['name' => '/some/path', 'tmp_name' => '/tmp/asdasd', 'size' => 0, 'type'=> '', 'error' => UPLOAD_ERR_OK],
                ['basename' => true, 'use_uploaded_name' => true],
                'path'
            ],
            [
                ['name' => 'asd', 'tmp_name' => '/tmp/asdasd', 'size' => 0, 'type'=> '', 'error' => UPLOAD_ERR_NO_FILE],
                [],
                ''
            ],
            [
                ['name' => 'asd', 'tmp_name' => '/tmp/asdasd', 'size' => 0, 'type'=> '', 'error' => UPLOAD_ERR_CANT_WRITE],
                [],
                ''
            ],
            [
                'asd',
                [],
                'asd'
            ],
            [
                [],
                [],
                ''
            ],
            [
                new \stdClass(),
                [],
                ''
            ],
            [
                new TestAsset\StringConvertible(),
                [],
                'MyBase\Test\Filter\TestAsset\StringConvertible'
            ],
        ];
    }
}
