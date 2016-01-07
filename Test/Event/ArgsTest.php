<?php

namespace Tale\Test\Event;

use Tale\Event\Args;

class ArgsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider constructAndSetDataProvider
     */
    public function testConstructAndSetData($data, $expected)
    {

        $args = new Args($data);

        $this->assertEquals($expected, $args->getData());
    }

    public function constructAndSetDataProvider()
    {

        return [
            [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]],
            [123, ['value' => 123]],
            [123.123, ['value' => 123.123]],
            ['some string', ['value' => 'some string']],
            [false, ['value' => false]],
            [true, ['value' => true]],
            [0, ['value' => 0]],
            [null, []]
        ];
    }

    public function testMagicAccess()
    {

        $args = new Args(['items' => ['a' => 1, 'b' => 2]]);

        $args->items['a'] = 3;

        $this->assertEquals($args->items['a'], 3);
    }
}