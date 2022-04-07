<?php


namespace Platron\AtolV5\data_objects;

class MarkQuantity extends BaseDataObject
{

	/** @var int */
	protected $numerator;

	/** @var int */
	protected $denominator;

	/**
	 * @param int $numerator
	 */
	public function addNumerator($numerator)
	{
		$this->numerator = (int)$numerator;
	}

	/**
	 * @param int $denominator
	 */
	public function addDenominator($denominator)
	{
		$this->denominator = (int)$denominator;
	}

}
