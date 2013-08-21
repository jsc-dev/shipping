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

    public function testBlank()
    {
        $this->assertEquals(true, true);
    }

}
