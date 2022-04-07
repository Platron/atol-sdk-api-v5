<?php

namespace Platron\AtolV5\data_objects;

use Platron\AtolV5\handbooks\Vates;

class Vat extends BaseDataObject
{
	/** @var string */
	protected $type;
	/** @var float */
	protected $sum;

	/**
	 * Vat constructor
	 * @param Vates $type
	 */
	public function __construct(Vates $type)
	{
		$this->type = $type->getValue();
	}

	public function addSum($sum)
	{
		$this->sum = $this->getTaxAmount($sum);
	}

	/**
	 * Получить сумму налога
	 * @param float $amount
	 * @return float
	 */
	protected function getTaxAmount($amount)
	{
		switch ($this->type) {
			case Vates::NONE:
			case Vates::VAT0:
				return round(0, 2);
			case Vates::VAT10:
			case Vates::VAT110:
				return round($amount * 10 / 110, 2);
			case Vates::VAT20:
			case Vates::VAT120:
				return round($amount * 20 / 120, 2);
		}
	}
}