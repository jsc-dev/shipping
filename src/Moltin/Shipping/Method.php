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

class Method
{
    protected $paths   = array();
    protected $methods = array();
    protected $rates   = array();

    protected $_loaded = array();

    public function getMethod($name)
    {
        // Check the method exists and is loaded
        if ( ! array_key_exists($name, $this->methods) ) {
            throw new InvalidMethodException('The requested method was not found');
        }

        return $this->methods[$name];
    }

    public function methods($types = array())
    {
        // Loop paths
        foreach ( $this->paths as $path ) {

            // No need to load more than once
            if ( in_array($path, $this->_loaded) ) { continue; }

            // Loop methods
            foreach ( glob($path.'*') as $type ) {

                // Do we want to load this?
                if ( is_array($types) and ! empty($types) and ! in_array($type, $types) ) { continue; }

                // Get class
                $class      = basename($type);
                $class      = "Moltin\\Shipping\\Method\\{$class}\\{$class}";
                $reflection = new \ReflectionClass($class);

                // Skip classes with issues
                if ( ! $reflection->isInstantiable() ) { continue; }

                // Load methods, rates, etc.
                $this->addMethod(new $class);
            }

            // Add to loaded
            $this->_loaded[] = $path;
        }
    }

    public function addMethod($class)
    {
        // Add to global
        $this->methods[$class->name] = $class;

        // Add rates
        foreach ( $class->rates() as $rate ) {
            $this->addRate($class->name, $rate);
        }
    }

    public function addRate($name, $rate)
    {
        $rate['name']  = $name;
        $this->rates[] = $rate;
    }

}
