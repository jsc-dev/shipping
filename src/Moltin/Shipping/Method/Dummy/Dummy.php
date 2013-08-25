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

namespace Moltin\Shipping\Method\Dummy;

class Dummy extends \Moltin\Shipping\MethodAbstract
{

    public $name = 'Dummy';

    public function rates()
    {
        // Variables
        $rates = array();

        // Add some
        $rates[] = array(
            'id'     => 'DUMMY_01',
            'name'   => 'Test Rate 01',
            'price'  => 3.50,
            'limits' => array(
                'weight' => array(0, 100),
                'price'  => array(0, 64.99)
            )
        );

        $rates[] = array(
            'id'     => 'DUMMY_02',
            'name'   => 'Test Rate 02 (Free)',
            'price'  => 0,
            'limits' => array(
                'weight' => array(0, 100),
                'price'  => array(65)
            )
        );

        // Send it back
        return $rates;
    }

}
