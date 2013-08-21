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

class Method
{

	protected $methods = array();
	protected $rates   = array();

	public function methods()
	{
        // Loop methods
        foreach (glob(__DIR__.'/Method/*') as $type) {

            // Get class
            $class      = basename($type);
            $class      = "Moltin\\Shipping\\Method\\{$class}\\{$class}";
            $reflection = new \ReflectionClass($class);

            // Skip classes with issues
            if ( ! $reflection->isInstantiable() ) continue;
            
            // Load methods, rates, etc.
            $this->addMethod(new $class);
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