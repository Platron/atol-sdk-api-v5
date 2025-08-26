<?php

namespace Platron\AtolV5\data_objects;

class CashlessPayment extends BaseDataObject
{
	/** @var float */
	protected $sum;
	/** @var number */
	protected $method;
	/** @var string */
	protected $id;
	/** @var string */
	protected $additional_info;

	/**
	 * @param float $sum
	 * @param number $method
	 * @param string $id
	 */
	public function __construct(float $sum, $method, string $id)
	{
		$this->sum = $sum;
		$this->method = $method;
		$this->id = $id;
	}

	/**
	 * @param string $additional_info
	 * @return void
	 */
	public function setAdditionalInfo(string $additional_info)
	{
		$this->additional_info = $additional_info;
	}

}