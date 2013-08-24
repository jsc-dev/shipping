<?php

/**
* This file is part of Moltin shipping, a PHP package which
* provides shipping creation and management.
*
* Copyright (c) 2013 Moltin Ltd.
* http://github.com/moltin/shipping
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* @package moltin/shipping
* @author Jamie Holdroyd <jamie@molt.in>
* @copyright 2013 Moltin Ltd.
* @version dev
* @link http://github.com/moltin/shipping
*
*/

namespace Moltin\Shipping\Method\DummyCallback;

class DummyCallback extends \Moltin\Shipping\MethodAbstract
{

    public $name = 'Dummy Callback';

    public function rates()
    {
        // Variables
        $rates = array();

        // Add some
        $rates[] = array(
            'name'   => 'Test Rate 01',
            'price'  => 3.50,
            'limits' => array(
                'weight' => array(0, 100),
                'price'  => array(0, 64.99)
            )
        );

        // Send it back
        return $rates;
    }

}
