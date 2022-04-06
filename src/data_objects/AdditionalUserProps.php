<?php

namespace Platron\AtolV5\data_objects;

class AdditionalUserProps extends BaseDataObject
{

	/** @var string */
	protected $name;

	/** @var string */
	protected $value;

	/**
	 * @param string $name
	 */
	public function addName($name)
	{
		$this->name = (string)$name;
	}

	/**
	 * @param string $value
	 */
	public function addValue($value)
	{
		$this->value = (string)$value;
	}

}
