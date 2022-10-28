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
	 * @param string $value
	 * @param string $timestamp
	 */
	public function __construct($name,$value,$timestamp)
	{
		$this->name = (string)$name;
		$this->value = (string)$value;
		$this->timestamp = (string)$timestamp;
	}
}
