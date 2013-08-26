<?php

// Required for sessions
ob_start();

use Moltin\Shipping\Method;
use Moltin\Shipping\Shipping;
use Moltin\Shipping\Storage\Session as Storage;

class MethodTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->shipping = new Shipping(new Storage);
    }

    public function tearDown()
    {
        $this->shipping = null;
    }

    public function testGetMethod()
    {
        // Load basic gateway
        $method = $this->shipping->getMethod('Flatrate');

        $this->assertEquals(is_object($method), true);
    }

    /**
    * @expectedException Moltin\Shipping\Exception\InvalidMethodException
    */
    public function testBadGetMethod()
    {
        $this->shipping->getMethod('Not A Real Method');
    }

    /*public function testAddPath()
    {
        // Load path and get dummy
        $this->shipping->addPath(__DIR__.'\\TestMethods\\');
        $method = $this->shipping->getMethod('Dummy');

        $this->assertEquals(is_object($method), true);
    }*/

}
