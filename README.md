# Transformer

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

[![SensioLabsInsight][ico-sensiolabs]][link-sensiolabs]


Framework agnostic package to transform arrays, objects, arrays of arrays or arrays of objects by manipulating, casting and mapping their properties and keys.


## Install

Via Composer

```bash
$ composer require cerbero/transformer
```


## Usage

Transformers can be created by extending the `AbstractTransformer` class:

```php
use Cerbero\Transformer\AbstractTransformer;

class MyTransformer extends AbstractTransformer
{
    protected function getStructure()
    {
        // the array structure you want to obtain
    }
}
```

The method `getStructure()` lets you define how you want the data to be transformed. For example, imagine that this array:

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
    'date'            => '2015-01-01T00:00:00+00:00',
    'number'          => '22',
    'cents'           => '$0.0500',
    'unneeded'        => true,
    'json'            => '{"foo":"bar"}',
]
```

has to be transformed into:

```php
[
    'key' => 'hello',
    'favorites' => [
        'color'  => 'blue',
        'number' => 22,
    ],
    'is_admin'  => true,
    'status'    => 2,
    'important' => [
        'date'  => '2015-01-01',
    ],
    'cents'     => 0.05,
    'json'      => ['foo' => 'bar'],
]
```

All you need to do is implement `getStructure()` as follows:

```php
protected function getStructure()
{
  return [
    'key' => 'some.nested.key',
    'favorites' => [
        'color'  => 'color',
        'number' => 'number int',
    ],
    'is_admin'       => 'adminPermission bool',
    'status'         => 'status enum:denied=0,accepted=1,pending=2',
    'important.date' => 'date date:Y-m-d',
    'cents'          => 'cents substr:1|float',
    // The `unneeded` key is not defined, so it will be ignored
    'json'           => 'json arr',
  ];
}
```

In the example above:
+ keys are exactly the keys and nested keys that you expect as a result
+ values are the old keys and can optionally contain some transformations
+ transformations are separated from old keys by space and follow the syntax: `transformation1:param1,param2|transformation2`
+ nested keys are defined by using dot notation, e.g.: `some.nested.key`.
+ keys that are not present in the array are automatically ignored.

After defining the new structure, you can transform arrays, objects, arrays of arrays or arrays of objects by calling the `transform()` method:

```php
$transformer = new MyTransformer($data);
$transformed = $transformer->transform();

// or by using the static factory method:
$transformed = MyTransformer::from($data)->transform();
```

or if you prefer to transform data into a specific object, you may call the method `transformInto()`:

```php
$transformer = new MyTransformer($data);
$transformed = $transformer->transformInto(new DataTransferObject);

// or by using the static factory method:
$transformed = MyTransformer::from($data)->transformInto(new DataTransferObject);
```


### Normalize many sources

Sometimes you may need to normalize data from different sources. In this case only source keys might change, whilst expected keys and transformations remain the same. To avoid repeating code for every source, you may extend the abstract transformer as you normally would but without specifying the source keys:

```php
use Cerbero\Transformer\AbstractTransformer;

abstract class MyTransformer extends AbstractTransformer
{
    protected function getStructure()
    {
        return [
            'key' => null,
            'favorites' => [
                'color'  => null,
                'number' => 'int',
            ],
            'is_admin'       => 'bool',
            'status'         => 'enum:denied=0,accepted=1,pending=2',
            'important.date' => 'date:Y-m-d',
            'cents'          => 'substr:1|float',
            'json'           => 'arr',
        ];
    }
}
```

The example above defines only expected keys and related transformations, no source keys. If no transformation is needed, `null` can be set as value.

Finally to map expected keys with keys of different sources, you may now extend your new transformer for each source and implement the method `getKeysMap()`:

```php
class Source1Transformer extends MyTransformer
{
    protected function getKeysMap()
    {
        return [
            'key' => 'some.nested.key',
            'favorites' => [
                'color'  => 'color',
                'number' => 'number',
            ],
            'is_admin'       => 'adminPermission',
            'status'         => 'status',
            'important.date' => 'date',
            'cents'          => 'cents',
            'json'           => 'json',
        ];
    }
}
```


## Transformations

Any function or class method can be used as a transformation, for example `trim`, `substr:2`, `Class::staticMethod`, `Namespace\Class::instanceMethod` are all valid transformations. Please note that the value to transform is passed as first parameter of the invoked function or method.

Furthermore this package comes with some builtin transformations:

| Transformation | Description                                                     | Syntax                                                    |
|----------------|-----------------------------------------------------------------|-----------------------------------------------------------|
| arr            | Decode a JSON or wrap the value into an array                   | `arr`                                                     |
| bool           | Cast the value to a boolean                                     | `bool`                                                    |
| date           | Transform the value into a DateTime object or format a date     | `date` `date:Y-m-d` `date:Y-m-d,UTC` `date:Y-m-d,UTC,UTC` |
| default        | Fallback to a default value if the value is `null` or not found | `default:foo`                                             |
| enum           | Enumerate the value with defined associations                   | `enum:denied=0,approved=1,pending=2`                      |
| float          | Cast the value to a float and optionally truncate decimals      | `float` `float:2`                                         |
| int            | Cast the value to an integer                                    | `int`                                                     |
| object         | Decode a JSON or convert the value to an object                 | `object`                                                  |
| string         | Encode a JSON or convert the value to a string                  | `string`                                                  |


### Custom transformations

When functions, methods and builtin transformations are not enough, you may implement your own transformations by adding methods to your transformer, for example:

```php
protected function prefix($prefix)
{
    return $prefix . $this->value;
}
```

`prefix` is now a custom transformation that receives a prefix as parameter and prepend it to the value to transform. It can now be applied by using the syntax `prefix:foo` so that the `$prefix` parameter equals `'foo'`. The above example only needs a parameter but you may pass as many arguments as you wish following the syntax `prefix:foo,bar,baz`.

Please note that `$this->value` holds the value obtained from the last transformation applied, so when you pipe more transformations the value is updated by every transformation it passes through.

Another handy property you may use in a custom transformation is `$this->item`, which holds the array or object that contains `$this->value`. It might be useful for example when a custom transformation requires another value from the original item to transform the current value.

Sometimes you may need the same custom transformation in different transformers. You might consider to extract such logic into a class so that it can be easily called from any transformer by using an alias, just like the builtin transformations:

```php
use Cerbero\Transformer\Transformations\AbstractTransformation;

class PrefixTransformation extends AbstractTransformation
{
    public function apply(array $parameters)
    {
        return $parameters[0] . $this->value;
    }
}
```

The custom transformation above extends the `AbstractTransformation` class and the parameters are passed in the `apply()` method as an array. Since `PrefixTransformation` follows the convention `Name + Transformation`, it can now be used with the alias `prefix` as we did before e.g.: `prefix:foo`.

Both the properties `$this->value` and `$this->item` are available in the transformation class, just like when you define custom transformations in your transformer.

Most likely your transformation classes are going to have their own namespace, it can be defined by implementing the method `getCustomTransformationNamespace()` in your transformer:

```php
protected function getCustomTransformationNamespace(): string
{
    return 'My\Namespace';
}
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Testing

``` bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.


## Security

If you discover any security related issues, please email andrea.marco.sartori@gmail.com instead of using the issue tracker.


## Credits

- [Andrea Marco Sartori][link-author]
- [All Contributors][link-contributors]


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-version]: https://img.shields.io/packagist/v/cerbero/transformer.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/cerbero90/Transformer/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cerbero90/Transformer.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cerbero90/Transformer.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/cerbero/transformer.svg?style=flat-square
[ico-sensiolabs]: https://insight.sensiolabs.com/projects/5932ecbf-aff3-48c8-9cf9-7639530a84f5/big.png

[link-packagist]: https://packagist.org/packages/cerbero/transformer
[link-travis]: https://travis-ci.org/cerbero90/Transformer
[link-scrutinizer]: https://scrutinizer-ci.com/g/cerbero90/Transformer/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cerbero90/Transformer
[link-downloads]: https://packagist.org/packages/cerbero/transformer
[link-author]: https://github.com/cerbero90
[link-contributors]: ../../contributors
[link-sensiolabs]: https://insight.sensiolabs.com/projects/5932ecbf-aff3-48c8-9cf9-7639530a84f5
