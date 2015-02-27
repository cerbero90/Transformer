<?php namespace Cerbero\Transformer;

use Illuminate\Support\Collection;

/**
 * Abstract implementation of a transformer.
 *
 * @author	Andrea Marco Sartori
 */
abstract class AbstractTransformer {

	/**
	 * @author	Andrea Marco Sartori
	 * @var		mixed	$original	The original value.
	 */
	protected $original;

	/**
	 * @author	Andrea Marco Sartori
	 * @var		array	$item	Processing item.
	 */
	protected $item;

	/**
	 * @author	Andrea Marco Sartori
	 * @var		mixed	$value	Processing value.
	 */
	protected $value;

	/**
	 * Retrieves the expected transformed value.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	array
	 */
	abstract protected function getStructure();

	/**
	 * Transform the given value.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	mixed	$value
	 * @return	mixed
	 */
	public function transform($value)
	{
		$this->original = $value;

		$collection = $this->forceCollection();

		$collection->transform(function($item)
		{
			return $this->transformItem($item);
		});

		return $this->originalIsOne() ? $collection->first() : $collection;
	}

	/**
	 * Retrieve a collection of items even if the original value is one.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	\Illuminate\Support\Collection
	 */
	protected function forceCollection()
	{
		if($this->originalIsOne())
		{
			return collect([$this->original]);
		}

		return collect($this->original);
	}

	/**
	 * Determine whether the original value is a single item.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	mixed	$value
	 * @return	boolean
	 */
	private function originalIsOne()
	{
		if( ! $this->originalCanBeLooped()) return true;

		foreach ((array) $this->original as $item)
		{
			if( ! is_array($item)) return true;
		}

		return false;
	}

	/**
	 * Determine whether original can be looped.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	boolean
	 */
	private function originalCanBeLooped()
	{
		$isTraversable = $this->original instanceof \Traversable;

		return is_array($this->original) || $isTraversable;
	}

	/**
	 * Transform a given item.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	mixed	$item
	 * @return	array
	 */
	protected function transformItem($item)
	{
		$this->item = [];

		foreach ($this->dotStructure() as $key => $rules)
		{
			$this->map($item, $key, $rules);
		}

		return $this->item;
	}

	/**
	 * Use dot notation to handle the structure given by the user.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	array
	 */
	private function dotStructure()
	{
		$structure = $this->getStructure();

		return array_dot($structure);
	}

	/**
	 * Map an item of a collection.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	mixed	$item
	 * @param	string	$key
	 * @param	string	$rules
	 * @return	void
	 */
	protected function map($item, $key, $rules)
	{
		$parser = new Parser($rules, $this->getCustomKey($key));

		$this->value = data_get($item, $parser->getKey());
		
		foreach ($parser->getTransformations() as $transformation => $args)
		{
			$this->value = call_user_func_array([$this, $transformation], $args);
		}

		array_set($this->item, $key, $this->value);
	}

	/**
	 * Retrieve one of the custom keys if set.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	string	$key
	 * @return	string|null
	 */
	private function getCustomKey($key)
	{
		$keys = $this->getCustomKeys();

		if(isset($keys[$key])) return $keys[$key];
	}

	/**
	 * Retrieve custom keys associated to source keys.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	array
	 */
	protected function getCustomKeys()
	{
		return [];
	}

	/**
	 * Dynamically apply transformations.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$name
	 * @param	array	$arguments
	 * @return	mixed
	 */
	public function __call($name, $arguments)
	{
		if( ! class_exists($class = $this->getTransformationByName($name)))
		{
			throw new \BadMethodCallException("Transformation [$name] is not supported by default.");
		}

		$transformation = new $class($this->value);

		return $transformation->apply($arguments);
	}

	/**
	 * Retrieve the class of the transformation with the given name.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$name
	 * @return	string
	 */
	protected function getTransformationByName($name)
	{
		$Name = ucfirst($name);

		return "Cerbero\Transformer\Transformations\\{$Name}";
	}

}