<?php

namespace Platron\AtolV5\data_objects;


class OperatingCheckProps extends BaseDataObject
{

	/** @var string */
	protected $name;

	/** @var string */
	protected $value;

	/** @var string */
	protected $timestamp;

	/**
	 * OperatingCheckProps constructor
	 * @param string $name
	 */
	public function __construct($name = "0")
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

	/**
	 * @param string $value
	 */
	public function addTimestamp($timestamp)
	{
		$this->timestamp = (string)$timestamp;
	}
}
