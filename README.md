![](http://s2.postimg.org/oubkkdn91/transformer.jpg "Transformer")
# Transformer

[![Author](http://img.shields.io/badge/author-@cerbero90-blue.svg?style=flat-square)](https://twitter.com/cerbero90)
[![Build Status](https://img.shields.io/travis/cerbero90/Transformer/master.svg?style=flat-square)](https://travis-ci.org/cerbero90/Transformer)
[![Packagist Version](https://img.shields.io/packagist/v/cerbero/transformer.svg?style=flat-square&label=release)](https://packagist.org/packages/cerbero/transformer)
[![Packagist](https://img.shields.io/packagist/l/cerbero/transformer.svg?style=flat-square)](LICENSE.md)
[![HHVM Support](https://img.shields.io/hhvm/cerbero/transformer.svg?style=flat-square)](https://travis-ci.org/cerbero90/Transformer)
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
All you have to do is to let `getStructure()` return the following array:
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
In this associative array:
+ keys are exactly the keys and nested keys you want to get as a result
+ values are the old keys and can optionally have some transformations
+ transformations are separated from old keys with a space and follow this syntax:
```
transformation1:param1,param2|transformation2
```

If you want to specify a nested old key, you can use the dot notation, e.g.: `some.nested.key`.
All keys that are not present within the returned array are automatically removed.
Now you can pass the object, array or multi-dimensional array you want to transform to the `transform()` method:
```php
$transformer = new MyTransformer;
$transformer->transform($arrayOrObject);
```
When you transform a multi-dimensional array, you get an instance of [Collection](http://laravel.com/api/5.0/Illuminate/Support/Collection.html). It can be used as a normal array but has many powerful methods to work with.

### Normalize many sources

Sometimes you may need to normalize data from different sources. In that case only the keys of the sources change, while the transformed keys and the applied transformations remain the same. To avoid repeating your code for every source, you can extend the abstract transformer as you did before but without specifying the source keys:

``` php
use Cerbero\Transformer\AbstractTransformer;

abstract class MyTransformer extends AbstractTransformer {

	protected function getStructure()
	{
		return [
			'key'  => null,
			'favorites' => [
				'color'  => null,
				'number' => 'int',
			],
			'is_admin'  => 'bool',
			'status'    => 'enum:denied=0,accepted=1,pending=2',
			'date'      => 'date:Y-m-d',
			'cents'     => 'float',
			'json'      => 'arr',
		];
	}

}
```
In this array there are only the transformed keys associated to the transformations to apply. If you don't need no transformation, just set `null`. To define the keys of the sources, extend the newly created transformer for every different source and override the method `getCustomKeys()`:

``` php
class SourceFooTransformer extends MyTransformer {

	protected function getCustomKeys()
	{
		return [
			'key'  => 'some.nested.key',
			'favorites' => [
				'color'  => 'color',
				'number' => 'number',
			],
			'is_admin'  => 'adminPermission',
			'status'    => 'status',
			'date'      => 'date',
			'cents'     => 'cents',
			'json'      => 'json',
		];
	}

}
```
This time the transformed keys are associated only to the original keys belonging to a given source, because the transformations to apply has been already set.

## Transformations

The following table shows the available default transformations:

| Transformation | Description                                                   | Syntax                               |
|:--------------:|---------------------------------------------------------------|--------------------------------------|
| arr            | Decode a JSON or wrap the value into an array                 | `arr`                                |
| bool           | Convert the value to a boolean                                | `bool`                               |
| date           | Convert the value to a DateTime object or format a date       | `date` `date:Y-m-d`                  |
| enum           | Enumerate the value with defined associations                 | `enum:denied=0,approved=1,pending=2` |
| float          | Convert the value to a float and optionally truncate decimals | `float` `float:2`                    |
| int            | Convert the value to an integer                               | `int`                                |
| object         | Decode a JSON or convert the value to an object               | `object`                             |
| string         | Encode a JSON or convert the value to a string                | `string`                             |

### Custom transformations

You can also define your own transformations by adding methods to your transformator, for example:
```php
protected function prefix($prefix)
{
	return $prefix . $this->value;
}
```
`prefix` is a custom transformation that receives a prefix as parameter and prepend it to the value to transform. It can now be applied with the syntax `prefix:foo` so that `$prefix` equals `'foo'`. The previous example needs only a parameter but you can pass how many arguments you want with the syntax `prefix:foo,bar,baz`.

Please note that `$this->value` holds the value got by the last transformation applied so when you chain more transformations, the value gets updated by every transformation it passes through.

There is another handy property you can use within a custom transformation: `$this->item` holds the array or object that contains `$this->value`. Useful for example when the custom transformation requires another value from the "container" to transform the current value.
