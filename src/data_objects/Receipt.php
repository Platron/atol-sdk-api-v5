<?php

namespace Platron\AtolV5\data_objects;

use Platron\AtolV5\handbooks\ReceiptOperationTypes;

class Receipt extends BaseDataObject
{
	/** @var Client */
	protected $client;
	/** @var Company */
	protected $company;
	/** @var Item[] */
	private $items;
	/** @var Payment[] */
	private $payments;
	/** @var string */
	private $operationType;
	/** @var string */
	private $additionalCheckProps;
	/** @var OperatingCheckProps */
	private $operatingCheckProps;
	/** @var AdditionalUserProps */
	private $additionalUserProps;
	/** @var SectoralBase */
	private $sectoralCheckProps;

	/**
	 * Document constructor.
	 * @param Client $client
	 * @param Company $company
	 * @param Item[] $items
	 * @param Payment $payment
	 * @param ReceiptOperationTypes $type
	 */
	public function __construct(Client $client, Company $company, $items, Payment $payment, ReceiptOperationTypes $type)
	{
		$this->client = $client;
		$this->company = $company;
		foreach($items as $item) {
			$this->addItem($item);
		}
		$this->addPayment($payment);
		$this->operationType = $type->getValue();
	}

	/**
	 * @param Item $item
	 */
	private function addItem(Item $item)
	{
		$this->items[] = $item;
	}

	/**
	 * @param Payment $payment
	 */
	public function addPayment(Payment $payment)
	{
		$this->payments[] = $payment;
	}

	/**
	 * @return float
	 */
	private function getItemsAmount()
	{
		$itemsAmount = 0;
		foreach ($this->items as $item) {
			$itemsAmount += $item->getPositionSum();
		}
		return $itemsAmount;
	}

	/**
	 * @return string
	 */
	public function getOperationType(){
		return $this->operationType;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		$params = parent::getParameters();

		foreach($this->items as $item) {
			$params['items'][] = $item->getParameters();
		}
		foreach($this->payments as $payment) {
			$params['payments'][] = $payment->getParameters();
		}

		$params['total'] = (double)$this->getItemsAmount();

		if (!empty($this->additionalCheckProps)) {
		    $params['additional_check_props'] = $this->additionalCheckProps;
        }

		if (!empty($this->operatingCheckProps)) {
		    $params['operating_check_props'] = $this->operatingCheckProps;
        }

		if (!empty($this->additionalUserProps)) {
		    $params['additional_user_props'] = $this->additionalUserProps;
        }

		if (!empty($this->sectoralCheckProps)) {
		    $params['sectoral_check_props'] = $this->sectoralCheckProps;
        }

		return $params;
	}

    /**
     * @param string $additionalCheckProps
     */
	public function setAdditionalCheckProps($additionalCheckProps)
    {
        if (!is_string($additionalCheckProps)) {
            throw new \InvalidArgumentException('Parameter additionalCheckProps should be string');
        }
        if (strlen($additionalCheckProps) > 16) {
            throw new \LengthException('Parameter additionalCheckProps should has length less than or equal 16');
        }
        $this->additionalCheckProps = $additionalCheckProps;
    }

	/**
	 * @param OperatingCheckProps $operatingCheckProps
	 */
	public function addOperatingCheckProps(OperatingCheckProps $operatingCheckProps)
	{
		$this->operatingCheckProps = $operatingCheckProps->getParameters();
	}

	/**
	 * @param AdditionalUserProps $additionalUserProps
	 */
	public function addAdditionalUserProps(AdditionalUserProps $additionalUserProps)
	{
		$this->additionalUserProps = $additionalUserProps->getParameters();
	}

	/**
	 * @param SectoralBase $sectoralCheckProps
	 */
	public function addSectoralCheckProps(SectoralBase $sectoralCheckProps)
	{
		$this->sectoralCheckProps[] = $sectoralCheckProps->getParameters();
	}
}