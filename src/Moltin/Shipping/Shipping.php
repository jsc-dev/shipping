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

	public function __construct(StorageInterface $storage)
	{
		// Build methods
		$this->methods();
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

		// Loop methods and calculate available
		foreach ( $this->methods as $name => $method ) {
			// Check methods
			if ( $this->checkMethods($method, $price, $weight) ) {
				$valid[] = $method;
			}
		}

		// Debug
		print_r($valid);
		exit();

		return $valid;
	}

	protected function checkMethod($method, $price, $weight)
	{
		print_r($method);
		exit();
	}

}
