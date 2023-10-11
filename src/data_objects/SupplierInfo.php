<?php

namespace Platron\AtolV5\data_objects;

class SupplierInfo extends BaseDataObject
{
	/** @var string[] */
	protected $phones;
	/** @var string */
	protected $name;
	/** @var int */
	protected $inn;

	/**
	 * Supplier constructor.
	 * @param $phones
	 */
	public function __construct($phones)
	{
		$this->phones[] = (string)$phones;

	}

	/**
	 * @param string $name
	 */
	public function addName($name)
	{
		$this->name = (string)$name;
	}

	/**
	 * @param int $inn
	 */
	public function addInn($inn)
	{
		$this->inn = (string)$inn;
	}
}