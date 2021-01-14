<?php

namespace Cerbero\Transformer;

use Illuminate\Support\Str;

/**
 * The transformation rules parser.
 *
 */
class Parser
{
    protected const KEY_SEPARATOR = ' ';
    protected const TRANSFORMATION_SEPARATOR = '|';
    protected const PARAMETER_LIST = ':';
    protected const PARAMETER_SEPARATOR = ',';

    /**
     * The raw rules to parse.
     *
     * @var string
     */
    protected $rawRules;

    /**
     * Set the dependencies.
     *
     * @param string|null $rawRules
     * @param string|null $customKey
     */
    public function __construct(string $rawRules = null, string $customKey = null)
    {
        $this->rawRules = $this->resolveRawRules($rawRules, $customKey);
    }

    /**
     * Resolve the raw rules to parse
     *
     * @param string|null $rawRules
     * @param string|null $customKey
     * @return string|null
     */
    protected function resolveRawRules(string $rawRules = null, string $customKey = null)
    {
        if ($customKey === null) {
            return $rawRules;
        }

        return $rawRules === null ? $customKey : $customKey . static::KEY_SEPARATOR . $rawRules;
    }

    /**
     * Retrieve the key for the current transformation
     *
     * @return string
     */
    public function parseKey(): string
    {
        return (string)Str::before($this->rawRules, static::KEY_SEPARATOR);
    }

    /**
     * Retrieve a map with transformations and parameters
     *
     * @return array
     */
    public function parseTransformations(): array
    {
        if (!$this->hasTransformations()) {
            return [];
        }

        $transformations = [];

        foreach ($this->getRawTransformations() as $rawTransformation) {
            $transformation = $this->parseTransformationName($rawTransformation);
            $transformations[$transformation] = $this->parseParameters($rawTransformation);
        }

        return $transformations;
    }

    /**
     * Determine whether the rules contain transformations
     *
     * @return bool
     */
    public function hasTransformations(): bool
    {
        return Str::contains($this->rawRules, static::KEY_SEPARATOR);
    }

    /**
     * Retrieve the transformations to parse
     *
     * @return array
     */
    protected function getRawTransformations(): array
    {
        $rawTransformations = Str::after($this->rawRules, static::KEY_SEPARATOR);

        return explode(static::TRANSFORMATION_SEPARATOR, $rawTransformations);
    }

    /**
     * Retrieve the parsed transformation name
     *
     * @param string $rawTransformation
     * @return string
     */
    public function parseTransformationName(string $rawTransformation): string
    {
        if (!$this->hasParameters($rawTransformation)) {
            return $rawTransformation;
        }

        $position = strrpos($rawTransformation, static::PARAMETER_LIST);

        return substr($rawTransformation, 0, $position);
    }

    /**
     * Retrieve the parameters of the given transformation to parse
     *
     * @param string $rawTransformation
     * @return array
     */
    public function parseParameters(string $rawTransformation): array
    {
        if (!$this->hasParameters($rawTransformation)) {
            return [];
        }

        $position = strrpos($rawTransformation, static::PARAMETER_LIST);
        $rawParameters = substr($rawTransformation, $position + 1);

        return explode(static::PARAMETER_SEPARATOR, $rawParameters);
    }

    /**
     * Determine whether the given transformation to parse has parameters
     *
     * @param string $rawTransformation
     * @return bool
     */
    public function hasParameters(string $rawTransformation): bool
    {
        // Remove instances of :: to avoid confusing parameters with static methods
        $sanitised = preg_replace('/::/', '', $rawTransformation);

        return Str::contains($sanitised, static::PARAMETER_LIST);
    }
}
