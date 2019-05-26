<?php

namespace Cerbero\Transformer;

use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * The abstract transformer.
 *
 */
abstract class AbstractTransformer
{
    use CallsTransformation;

    /**
     * The array or object to transform.
     *
     * @param array|object
     */
    protected $data;

    /**
     * The object to map data into.
     *
     * @var object|null
     */
    protected $object;

    /**
     * The current item.
     *
     * @var array|object
     */
    protected $item;

    /**
     * The item being processed.
     *
     * @var array|object
     */
    protected $processingItem;

    /**
     * The current value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Retrieve the expected structure of the transformed data
     *
     * @return array
     */
    abstract protected function getStructure(): array;

    /**
     * Set the dependencies.
     *
     * @param array|object $data
     */
    public function __construct($data)
    {
        $this->setData($data);
    }

    /**
     * Set the given data and check whether it is a matrix
     *
     * @param array|object $data
     * @return void
     */
    protected function setData($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new InvalidArgumentException('Only objects or arrays can be transformed.');
        }

        $this->data = $data;
    }

    /**
     * Create a new instance while easing method chaining
     *
     * @param array|object $data
     * @return self
     */
    public static function from($data): self
    {
        return new static($data);
    }

    /**
     * Transform the data into the given object
     *
     * @param object $object
     * @return array|object
     */
    public function transformInto($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Unable to transform data into the given value.');
        }

        $this->object = $object;

        return $this->transform();
    }

    /**
     * Transform the data
     *
     * @return array|object
     */
    public function transform()
    {
        $isCollection = !Arr::isAssoc((array)$this->data);
        $data = $isCollection ? $this->data : [$this->data];
        $transformedData = array_map([$this, 'transformItem'], $data);

        return $isCollection ? $transformedData : $transformedData[0];
    }

    /**
     * Transform the given item
     *
     * @param array|object $item
     * @return array|object
     */
    protected function transformItem($item)
    {
        if (!Arr::isAssoc((array)$item)) {
            throw new InvalidArgumentException('Only objects or associative arrays can be transformed.');
        }

        $this->item = $item;
        $this->processingItem = $this->object ? clone $this->object : [];
        $structure = Arr::dot($this->getStructure());

        foreach ($structure as $key => $rawRules) {
            $this->processItemKey($item, $key, $rawRules);
        }

        return $this->processingItem;
    }

    /**
     * Process the given key of the provided item
     *
     * @param array|object $item
     * @param string $key
     * @param string|null $rawRules
     * @return void
     */
    protected function processItemKey($item, string $key, string $rawRules = null)
    {
        $customKey = Arr::get($this->getKeysMap(), $key);
        $parser = new Parser($rawRules, $customKey);
        $this->value = data_get($item, $parser->parseKey());

        foreach ($parser->parseTransformations() as $transformation => $parameters) {
            $this->value = $this->callTransformation($transformation, $parameters);
        }

        data_set($this->processingItem, $key, $this->value);
    }

    /**
     * Retrieve the keys map in the format expected_key => original_key
     *
     * @return array
     */
    protected function getKeysMap(): array
    {
        return [];
    }
}
