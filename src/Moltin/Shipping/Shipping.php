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

class Shipping extends Method
{

    protected $args;

    public function __construct(StorageInterface $storage, $args = array())
    {
        // Set args
        $this->args = $args;

        // Build paths
        if ( isset($args['paths']) and is_array($args['paths']) ) { $this->paths = array_merge($this->paths, $args['paths']); }

        // Add default path and build methods
        $this->addPath(__DIR__.'/Method/');
    }

    public function addPath($path)
    {
        // Add to path
        $this->paths[] = $path;

        // Rebuild methods
        if ( isset($this->args['methods']) and is_array($this->args['methods']) ) { $this->methods($this->args['methods']); } else { $this->methods(); }
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
        print_r($valid);

        return $valid;
    }

    protected function checkRate(&$rate, $price, $weight)
    {
        // Check for callback
        if ( ! is_array($rate['limits']) and substr($rate['limits'], 0, 1) == '_' ) {
            if ( $this->methods[$rate['name']]->$rate['limits']($rate) ) { return true; }
            return false;
        }

        // Variables
        $price_min  = ( isset($rate['limits']['price'][0])  ? $rate['limits']['price'][0]  : null );
        $price_max  = ( isset($rate['limits']['price'][1])  ? $rate['limits']['price'][1]  : null );
        $weight_min = ( isset($rate['limits']['weight'][0]) ? $rate['limits']['weight'][0] : null );
        $weight_max = ( isset($rate['limits']['weight'][1]) ? $rate['limits']['weight'][1] : null );

        // Check pricing
        if ( $price_min !== null and $price < $price_min ) { return false; }
        if ( $price_max !== null and $price > $price_max ) { return false; }

        // Check weight
        if ( $weight !== null and $weight < $weight_min ) { return false; }
        if ( $weight !== null and $weight > $weight_max ) { return false; }

        return true;
    }

}
