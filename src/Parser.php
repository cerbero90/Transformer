<?php namespace Cerbero\Transformer;

/**
 * Parser for rules to apply.
 *
 * @author	Andrea Marco Sartori
 */
class Parser {

	const KEY_SEPARATOR    = ' ';
	
	const ACTION_SEPARATOR = '|';
	
	const ARGS_LIST        = ':';
	
	const ARGS_SEPARATOR   = ',';

	/**
	 * @author	Andrea Marco Sartori
	 * @var		string	$string	TransformString to parse.
	 */
	protected $string;
	
	/**
	 * Set the dependencies.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$string
	 * @param	string|null	$key
	 * @return	void
	 */
	public function __construct($string, $key = null)
	{
		if( ! is_null($key))
		{
			$string = $this->addKeyToString($key, $string);
		}

		$this->string = $string;
	}

	/**
	 * Add a custom key to the string to parse.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$key
	 * @param	string	$string
	 * @return	string
	 */
	private function addKeyToString($key, $string)
	{
		if( ! $string) return $key;

		return $key . static::KEY_SEPARATOR . $string;
	}

	/**
	 * Retrieve the key the original value is put in.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	string
	 */
	public function getKey()
	{
		if($this->hasTransformations())
		{
			return head($this->explodeKey());
		}

		return $this->string;
	}

	/**
	 * Determine whether the string has transformations.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	boolean
	 */
	protected function hasTransformations()
	{
		return str_contains($this->string, static::KEY_SEPARATOR);
	}

	/**
	 * Explode the key separator.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	array
	 */
	protected function explodeKey()
	{
		return explode(static::KEY_SEPARATOR, $this->string);
	}

	/**
	 * Retrieves the transformations to apply and their arguments.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	array
	 */
	public function getTransformations()
	{
		if( ! $this->hasTransformations()) return [];

		$transformations = [];

		foreach ($this->explodeActions() as $action)
		{
			$this->pushAction($action, $transformations);
		}

		return $transformations;
	}

	/**
	 * Explode the action separator.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	array
	 */
	protected function explodeActions()
	{
		$actions = last($this->explodeKey());

		return explode(static::ACTION_SEPARATOR, $actions);
	}

	/**
	 * Push the parsed action into a list.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$action
	 * @param	array	$list
	 * @return	void
	 */
	protected function pushAction($action, &$list)
	{
		if( ! $this->hasArguments($action)) return $list[$action] = [];

		list($key, $value) = $this->explodeAction($action);

		$list[$key] = $this->explodeArgs($value);
	}

	/**
	 * Determine whether an action has arguments.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$action
	 * @return	boolean
	 */
	protected function hasArguments($action)
	{
		return str_contains($action, static::ARGS_LIST);
	}

	/**
	 * Explode the given action.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$action
	 * @return	array
	 */
	protected function explodeAction($action)
	{
		return explode(static::ARGS_LIST, $action);
	}

	/**
	 * Explode the given arguments.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	string	$args
	 * @return	array
	 */
	protected function explodeArgs($args)
	{
		return explode(static::ARGS_SEPARATOR, $args);
	}

}
