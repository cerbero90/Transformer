<?php

namespace Cerbero\Transformer;

class SampleObject
{
    public $foo;
    public $money;
    public $key;
    public $new_key;
    public $package;
    public $baz;
    public $QUX;
    public $mid_char;

    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $parameter => $value) {
            $this->$parameter = $value;
        }
    }
}
