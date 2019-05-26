<?php

namespace Cerbero\Transformer;

/**
 * The sample transformer.
 *
 */
class SampleTransformer extends AbstractTransformer
{
    /**
     * The overridden structure.
     *
     * @var array
     */
    protected $structure;

    /**
     * Retrieve the expected structure of the transformed data
     *
     * @return array
     */
    protected function getStructure(): array
    {
        if (isset($this->structure)) {
            return $this->structure;
        }

        return [
            'foo' => 'foo',
            'money' => 'money substr:1|float:2',
            'key' => 'nested.key int',
            'new_key' => 'no_key default:bar',
            'package.name' => 'name initials',
            'baz' => 'Illuminate\Support\Str::after:hello',
            'QUX' => 'qux Illuminate\Support\Str::upper',
            'mid_char' => 'chars sampleCustom',
        ];
    }

    /**
     * Retrieve the keys map in the format expected_key => original_key
     *
     * @return array
     */
    protected function getKeysMap(): array
    {
        return [
            'baz' => 'greet',
        ];
    }

    /**
     * Retrieve the custom namespace for transformations
     *
     * @return string
     */
    protected function getCustomTransformationNamespace(): string
    {
        return 'CerberoTransformer\CustomTransformations';
    }

    /**
     * Custom transformation retrieving the initials of the given name
     *
     * @return string
     */
    protected function initials(): string
    {
        preg_match_all("/[A-Z]/", ucwords(strtolower($this->value)), $matches);

        return implode('', $matches[0]);
    }

    /**
     * Method added to simplify tests against expected structures
     *
     * @param array $structure
     * @return self
     */
    public function overrideStructure(array $structure): self
    {
        $this->structure = $structure;

        return $this;
    }
}

namespace CerberoTransformer\CustomTransformations;

use Cerbero\Transformer\Transformations\AbstractTransformation;

class SampleCustomTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return void
     */
    public function apply(array $parameters)
    {
        $position = floor(strlen($this->value) / 2);

        return substr($this->value, $position, 1);
    }
}

class InvalidCustomTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return void
     */
    public function apply(array $parameters)
    {
        $position = floor(strlen($this->value) / 2);

        return substr($this->value, $position, 1);
    }
}
