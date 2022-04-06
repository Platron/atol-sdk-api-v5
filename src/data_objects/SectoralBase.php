<?php

namespace Platron\AtolV5\data_objects;

class SectoralBase extends BaseDataObject
{

	/** @var string */
	protected $federal_id;

	/** @var string */
	protected $date;

	/** @var string */
	protected $number;

	/** @var string */
	protected $value;

	/**
	 * SectoralBase constructor
	 * @param string $federalID
	 */
	public function __construct($federalID)
	{
		$this->federal_id = (string)$federalID;
	}

	/**
	 * @param string $date
	 */
	public function addDate($date)
	{
		$this->date = (string)$date;
	}

	/**
	 * @param string $number
	 */
	public function addNumber($number)
	{
		$this->number = (string)$number;
	}

	/**
	 * @param string $date
	 */
	public function addValue($value)
	{
		$this->value = (string)$value;
	}


}
