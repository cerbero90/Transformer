![](http://s2.postimg.org/oubkkdn91/transformer.jpg "Transformer")
# Transformer

[![Author](http://img.shields.io/badge/author-@cerbero90-blue.svg?style=flat-square)](https://twitter.com/cerbero90)
[![Build Status](https://img.shields.io/travis/cerbero90/Transformer/master.svg?style=flat-square)](https://travis-ci.org/cerbero90/Transformer)
[![Packagist Version](https://img.shields.io/packagist/v/cerbero/transformer.svg?style=flat-square&label=release)](https://packagist.org/packages/cerbero/transformer)
[![Packagist](https://img.shields.io/packagist/l/cerbero/transformer.svg?style=flat-square)](LICENSE.md)
[![Code Climate](https://img.shields.io/codeclimate/github/cerbero90/Transformer.svg?style=flat-square)](https://codeclimate.com/github/cerbero90/Transformer)
[![Quality Score](https://img.shields.io/scrutinizer/g/cerbero90/Transformer.svg?style=flat-square)](https://scrutinizer-ci.com/g/cerbero90/Transformer)
[![Gratipay](https://img.shields.io/gratipay/cerbero.svg?style=flat-square)](https://gratipay.com/cerbero/)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5932ecbf-aff3-48c8-9cf9-7639530a84f5/big.png)](https://insight.sensiolabs.com/projects/5932ecbf-aff3-48c8-9cf9-7639530a84f5)

Framework agnostic package to transform objects and arrays by manipulating, casting and mapping their properties.

## Install

Run this command in the root of your project:

```
composer require cerbero/transformer
```

## Usage

In order to create a new transformer, extend the abstract class:

``` php
use Cerbero\Transformer\AbstractTransformer;

class MyTransformer extends AbstractTransformer {

	protected function getStructure()
	{
		// the resulting array you want to get
	}

}
```
The abstract class let you implement your own `getStructure()` method to define how the returned array should be.

For example, suppose to have this array:
```php
[
  'some' => [
      'nested' => [
          'key' => 'hello',
      ],
  ],
  'color'           => 'blue',
  'adminPermission' => '1',
  'status'          => 'pending',
  'date'            => '2015-01-01T12:00:00+01:00',
  'number'          => '22',
  'cents'           => '0.0500',
  'mood'            => 'sad',
  'json'            => '{"foo":"bar"}',
]
```
and want it to be converted into:
```php
[
  'key'  => 'hello',
  'favorites' => [
      'color'  => 'blue',
      'number' => 22,
  ],
  'is_admin'  => true,
  'status'    => 2,
  'date'      => '2015-01-01',
  'cents'     => 0.05,
  'json'      => ['foo' => 'bar'],
]
```
All we have to do is to let `getStructure()` return the following array:
```php
protected function getStructure()
{
	return [
		'key'  => 'some.nested.key',
		'favorites' => [
			'color'  => 'color',
			'number' => 'number int',
		],
		'is_admin'  => 'adminPermission bool',
		'status'    => 'status enum:denied=0,accepted=1,pending=2',
		'date'      => 'date date:Y-m-d',
		'cents'     => 'cents float',
		// We don't want to be sad, so let's remove the 'mood' item and smile :) 
		'json'      => 'json arr',
	];
}
```
As we can see, in this associative array
+ keys are exactly the keys and nested keys we want to get as a result
+ values are the old keys and can optionally have some transformations

If we want to specify a nested old key, we can use the dot notation, e.g.: `some.nested.key`.

All keys that are not present within the returned array are automatically removed.

Transformations can be defined after the old keys using the following syntax:

  old.key transformation1|transformation2:param1,param2

We can add many transformations and pass many parameters to them. The default transformations are:
+ arr
+ bool
+ date
+ enum
+ float
+ int
+ object
+ string
