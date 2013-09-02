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

    public function testAddCustomMethod()
    {
        // Load dummy and add it
        require(__DIR__.'\\TestMethods\\Dummy\\Dummy.php');
        $dummy = new \Dummy();
        $this->shipping->addMethod($dummy);

        // Get the method from shipping
        $method = $this->shipping->getMethod('Dummy');

        $this->assertEquals(is_object($method), true);
    }

    public function testCallbackPrice()
    {
        // Variables
        $sep = DIRECTORY_SEPARATOR;

        // Load dummy and add it
        require(__DIR__."{$sep}TestMethods{$sep}DummyCallback{$sep}DummyCallback.php");
        $dummy = new \DummyCallback();
        $this->shipping->addMethod($dummy);

        // Calculate to fire callback
        $this->shipping->getValid(65.00, 10.00);

        // Get the rate
        $rate = $this->shipping->getRate('DUMMY_CALL_01');

        $this->assertEquals($rate['price'], 3.50);
    }

}
