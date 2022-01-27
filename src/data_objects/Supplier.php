<?php

namespace Platron\AtolV5\data_objects;

class Supplier extends BaseDataObject
{
	/** @var string[] */
	protected $phones;
	/** @var string */
	protected $name;
	/** @var int */
	protected $inn;

	/**
	 * Supplier constructor.
	 * @param $name
	 */
	public function __construct($name)
	{
		$this->name = (string)$name;
	}

	/**
	 * @param string $phone
	 */
	public function addPhone($phone)
	{
		$this->phones[] = (string)$phone;
	}

	/**
	 * @param int $inn
	 */
	public function addInn($inn)
	{
		$this->inn = (string)$inn;
	}
}