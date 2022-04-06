<?php

namespace Platron\AtolV5\data_objects;

use Platron\AtolV5\handbooks\CorrectionOperationTypes;

class Correction extends BaseDataObject
{
	/** @var Company */
	protected $company;
	/** @var CorrectionInfo */
	protected $correction_info;
	/** @var Payment[] */
	private $payments;
	/** @var Vat */
	private $vats;
	/** @var CorrectionOperationTypes */
	private $operationType;
	/** @var Item[] */
	protected $items;

	/**
	 * Correction constructor.
	 * @param CorrectionOperationTypes $operationType
	 * @param Company $company
	 * @param CorrectionInfo $correctionInfo
	 * @param Payment $payment
	 * @param Vat $vat
	 * @param Item[] $items
	 */
	public function __construct(CorrectionOperationTypes $operationType, Company $company, CorrectionInfo $correctionInfo, Payment $payment, Vat $vat, $items)
	{
		$this->operationType = $operationType->getValue();
		$this->company = $company;
		$this->correction_info = $correctionInfo;
		$this->addPayment($payment);
		$this->addVat($vat);
		foreach ($items as $item) {
			$this->addItem($item);
		}
	}

	/**
	 * @param Item $item
	 */
	private function addItem(Item $item)
	{
		$this->items[] = $item->getParameters();
	}

	/**
	 * @param Payment $payment
	 */
	public function addPayment(Payment $payment)
	{
		$this->payments[] = $payment;
	}

	/**
	 * @param Vat $vat
	 */
	public function addVat(Vat $vat)
	{
		$this->vats[] = $vat;
	}

	/**
	 * @return string
	 */
	public function getOperationType()
	{
		return $this->operationType;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		$parameters = parent::getParameters();
		$total = 0;
		foreach ($this->payments as $payment) {
			$parameters['payments'][] = $payment->getParameters();
			$total += $payment->getParameters()['sum'];
		}
		foreach ($this->vats as $vat) {
			$parameters['vats'][] = $vat->getParameters();
		}
		$parameters['total'] = $total;
		return $parameters;
	}
}