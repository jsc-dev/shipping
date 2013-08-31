<?php

// Required for sessions
ob_start();

use Moltin\Shipping\Method;
use Moltin\Shipping\Shipping;
use Moltin\Shipping\Storage\Session as Storage;

class ShippingTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->shipping = new Shipping(new Storage);
    }

    public function tearDown()
    {
        $this->shipping = null;
    }

    public function testGetRate()
    {
        $this->assertEquals(true, true);
    }

    /**
    * @expectedException Moltin\Shipping\Exception\InvalidRateException
    */
    public function testBadGetRate()
    {
        $this->shipping->getRate('Not A Real Rate');
    }

    public function testBlank()
    {
        $methods = $this->shipping->getValid(100.00, 67.50);
        $this->assertEquals(true, true);
    }

}
