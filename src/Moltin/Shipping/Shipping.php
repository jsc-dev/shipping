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

namespace Moltin\Shipping;

use Moltin\Shipping\Exception\InvalidMethodException;
use Moltin\Shipping\Exception\InvalidRateException;

class Shipping extends Method
{

    protected $args;

    public function __construct(StorageInterface $storage, $args = array())
    {
        // Set args
        $this->args = $args;

        // Add default path and build methods
        $this->paths[] = __DIR__.'/Method/';
        if ( isset($args['methods']) and is_array($args['methods']) ) { $this->methods($args['methods']); } else { $this->methods(); }
    }

    public function calculate(\Moltin\Cart\Cart $cart)
    {
        // Variables
        $price  = $cart->total();
        $weight = 0;

        // Loop and build weight
        foreach ( $cart->contents() as $item ) { $weight += (float)$item->weight; }

        // Get available methods
        return $this->getValid($price, $weight);
    }

    public function getValid($price, $weight)
    {
        // Variables
        $valid = array();

        // Loop rates and calculate available
        foreach ( $this->rates as $name => &$rate ) {
            // Check rate
            if ( $this->checkRate($rate, $price, $weight) ) {
                $valid[] = $rate;
            }
        }

        // Debug
        // print_r($valid);

        return $valid;
    }

    public function getRate($id)
    {
        // Loop and find rate
        foreach ( $this->rates as $rate ) {
            if ( $rate['id'] == $id ) { return $rate; }
        }

        // Not found
        throw new InvalidRateException('The requested rate was not found');
    }

    protected function checkRate(&$rate, $price, $weight)
    {
        // Check price
        if ( isset($rate['limits']['price']) ) {

            // Get values
            $price_min  = ( isset($rate['limits']['price'][0])  ? $rate['limits']['price'][0]  : null );
            $price_max  = ( isset($rate['limits']['price'][1])  ? $rate['limits']['price'][1]  : null );

            // Check
            if ( $price_min !== null and $price < $price_min ) { return false; }
            if ( $price_max !== null and $price > $price_max ) { return false; }
        }

        // Check weight
        if ( isset($rate['limits']['weight']) ) {

            // Get values
            $weight_min  = ( isset($rate['limits']['weight'][0])  ? $rate['limits']['weight'][0]  : null );
            $weight_max  = ( isset($rate['limits']['weight'][1])  ? $rate['limits']['weight'][1]  : null );

            // Check
            if ( $weight_min !== null and $weight < $weight_min ) { return false; }
            if ( $weight_max !== null and $weight > $weight_max ) { return false; }
        }

        // Check callback
        if ( isset($rate['limits']['callback']) ) {
            if ( ! $this->methods[$rate['name']]->$rate['limits']['callback']($rate) ) { return false; }
        }

        return true;
    }

}
