<?php

namespace Cerbero\Transformer;

use BadMethodCallException;
use Cerbero\Transformer\Transformations\AbstractTransformation as Transformation;
use Throwable;
use Illuminate\Support\Str;

/**
 * The calls transformation trait.
 *
 */
trait CallsTransformation
{
    /**
     * Resolve and call the given transformation
     *
     * @param string $transformation
     * @param array $parameters
     * @return mixed
     */
    protected function callTransformation(string $transformation, array $parameters)
    {
        // Call custom transformation in the current transformer if implemented
        if (is_callable([$this, $transformation])) {
            return call_user_func_array([$this, $transformation], $parameters);
        }

        // Call custom transformation class if implemented
        if (class_exists($customTransformation = $this->qualifyTransformation($transformation, true))) {
            return $this->callCustomTransformation($customTransformation, $parameters);
        }

        // Call function or method if exists
        if (is_callable($transformation)) {
            array_unshift($parameters, $this->value);
            return $this->callCallableTransformation($transformation, $parameters);
        }

        // Call internal transformations if exists
        if (class_exists($internalTransformation = $this->qualifyTransformation($transformation))) {
            return $this->callCustomTransformation($internalTransformation, $parameters);
        }

        throw new BadMethodCallException("Unable to call the transformation {$transformation}");
    }

    /**
     * Retrieve the fully qualified class name of the given transformation
     *
     * @param string $transformation
     * @param bool $isCustom
     * @return string
     */
    protected function qualifyTransformation(string $transformation, bool $isCustom = false): string
    {
        $namespace = $isCustom ? $this->getCustomTransformationNamespace() : 'Cerbero\Transformer\Transformations';

        return Str::finish($namespace, '\\') . ucfirst($transformation) . 'Transformation';
    }

    /**
     * Retrieve the custom namespace for transformations
     *
     * @return string
     */
    protected function getCustomTransformationNamespace(): string
    {
        return '';
    }

    /**
     * Call the given custom transformation
     *
     * @param string $transformation
     * @param array $parameters
     * @return mixed
     */
    protected function callCustomTransformation(string $transformation, array $parameters)
    {
        if (is_subclass_of($transformation, Transformation::class)) {
            return (new $transformation($this->value, $this->item))->apply($parameters);
        }

        throw new BadMethodCallException('Custom transformations need to implement ' . Transformation::class);
    }

    /**
     * Call the given callable transformation
     *
     * @param string $transformation
     * @param array $parameters
     * @return mixed
     */
    protected function callCallableTransformation(string $transformation, array $parameters)
    {
        // Try to call a function or a method statically
        try {
            return call_user_func_array($transformation, $parameters);
        } catch (Throwable $e) {
            // Try to call a method from a class instance
            try {
                $callable = explode('::', $transformation);
                return call_user_func_array([new $callable[0](), $callable[1] ?? ''], $parameters);
            } catch (Throwable $e) {
                throw new BadMethodCallException("Unable to call {$transformation}: " . $e->getMessage());
            }
        }
    }
}
