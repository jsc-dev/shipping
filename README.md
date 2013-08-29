# Shipping Package

[![Build Status](https://secure.travis-ci.org/moltin/shipping.png)](http://travis-ci.org/moltin/shipping)
[![Total Downloads](https://poser.pugx.org/moltin/shipping/downloads.png)](https://packagist.org/packages/moltin/shipping)
[![Latest Stable Version](https://poser.pugx.org/moltin/shipping/v/stable.png)](https://packagist.org/packages/moltin/shipping)

The Moltin shipping composer package is a modular way to manage shipping packages either
via a table-rate system or via third-party integrations with many of the major global
shipping companies.


## Install

Via Composer

``` json
{
    "require": {
        "moltin/shipping": "~1.0"
    }
}
```


## Usage

Below is a basic usage guide for this package.

### Instantiating the Package

Before you begin you will need to instantiate the package.

``` php
use Moltin\Shipping\Method;
use Moltin\Shipping\Shipping;
use Moltin\Shipping\Storage\Session as Storage;


$shipping = new Shipping(new Storage);
```

A number of custom arguments can be passed as an array as the second argument when instantiating the cart, including:

``` php
$args = array(
	'methods' => array(), // A list of shipping driver names to load, defaults to loading all
);

$shipping = new Shipping(new Storage, $args);
```

### Adding a Custom Driver

If you would like to load a custom driver, you can do so as follows:

``` php
$driver = new MyDriver();
$shipping->addMethod($driver);
```

The driver will then be automatically processed and available for all subsequent methods.

### Calculating Shipping Methods

There are two main ways to calculate the shipping methods that are relevant to your items.

#### Manually

Firstly you can use the following method, passing in $price and $weight of the items you are shipping:

``` php
$methods = $shipping->getValid(65.00, 3.50);
var_dump($methods);
```

*Note:* Whenever you pass a weight the package and its' drivers expect to use KGs

#### From a Cart

Secondly, if you are using the Moltin/Cart package you can pass your cart object and it will
calculate the above values for you and pass back the available methods.

``` php
$methods = $shipping->calculate($cart);
var_dump($methods);
```



## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/moltin/shipping/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Moltin](https://github.com/moltin)
- [All Contributors](https://github.com/moltin/shipping/contributors)


## License

Please see [License File](https://github.com/moltin/shipping/blob/master/LICENSE) for more information.
