<?php

namespace Platron\AtolV5\data_objects;

use Platron\AtolV5\handbooks\PaymentMethods;
use Platron\AtolV5\handbooks\PaymentObjects;


class Item extends BaseDataObject
{

	/** @var float */
	protected $sum;
	/** @var Vat */
	protected $vat;
	/** @var string */
	protected $name;
	/** @var float */
	protected $price;
	/** @var int */
	protected $quantity;
	/** @var string */
	protected $measure;
	/** @var string */
	protected $payment_method;
	/** @var string */
	protected $payment_object;
	/** @var AgentInfo */
	protected $agent_info;
	/** @var MarkQuantity */
	protected $mark_quantity;
	/** @var Supplier */
	protected $supplier_info;
	/** @var string */
	protected $user_data;
	/** @var string */
	protected $nomenclature_code;
	/** @var float */
	protected $excise;
	/** @var string */
	protected $country_code;
	/** @var string */
	protected $declaration_number;
	/** @var string */
	protected $mark_processing_mode;
	/** @var MarkCode */
	protected $mark_code;
	/** @var SectoralItemProps[] */
	private $sectoralItemProps;

	/**
	 * Item constructor
	 * @param string $name Описание товара
	 * @param double $price Цена единицы товара
	 * @param float $quantity Количество товара
	 * @param Vat $vat
	 * @param double $sum Сумма количества товаров. Передается если количество * цену товара не равно sum
	 */
	public function __construct($name, $price, $quantity, Vat $vat, $sum = null)
	{
		$this->name = (string)$name;
		$this->price = (double)$price;
		$this->quantity = (double)$quantity;
		if (!$sum) {
			$this->sum = (double)$this->quantity * $this->price;
		} else {
			$this->sum = (double)$sum;
		}
		$this->vat = $vat;
	}

	/**
	 * Получить сумму позиции
	 * @return float
	 */
	public function getPositionSum()
	{
		return $this->sum;
	}

	/**
	 * @param int $measure
	 */
	public function addMeasure($measure)
	{
		$this->measure = (int)$measure;
	}

	/**
	 * @param PaymentMethods $paymentMethod
	 */
	public function addPaymentMethod(PaymentMethods $paymentMethod)
	{
		$this->payment_method = $paymentMethod->getValue();
	}

	/**
	 * @param PaymentObjects $paymentObject
	 */
	public function addPaymentObject(PaymentObjects $paymentObject)
	{
		$this->payment_object = $paymentObject->getValue();
	}

	/**
	 * @param AgentInfo $agentInfo
	 */
	public function addAgentInfo(AgentInfo $agentInfo)
	{
		$this->agent_info = $agentInfo;
		$this->supplier_info = $agentInfo->getSupplierInfo();
	}

	/**
	 * @param MarkQuantity $markQuantity
	 */
	public function addMarkQuantity(MarkQuantity $markQuantity)
	{
		$this->mark_quantity = $markQuantity;
	}

	/**
	 * @param string $userData
	 */
	public function addUserData($userData)
	{
		$this->user_data = (string)$userData;
	}

	/**
	 * @param string $nomenclatureCode
	 */
	public function addNomenclatureCode($nomenclatureCode)
	{
		$this->nomenclature_code = (string)$nomenclatureCode;
	}

	/**
	 * @param float $excise
	 */
	public function addExcise($excise)
	{
		$this->excise = $excise;
	}

	/**
	 * @param string $countryCode
	 */
	public function addCountryCode($countryCode)
	{
		$this->country_code = (string)$countryCode;
	}

	/**
	 * @param string $declarationNumber
	 */
	public function addDeclarationNumber($declarationNumber)
	{
		$this->declaration_number = (string)$declarationNumber;
	}

	/**
	 * @param string $markProcessingMode
	 */
	public function addMarkProcessingMode($markProcessingMode)
	{
		$this->mark_processing_mode = (string)$markProcessingMode;
	}

	/**
	 * @param MarkCode $markCode
	 */
	public function addMarkCode(MarkCode $markCode)
	{
		$this->mark_code = $markCode;
	}

	/**
	 * @param SectoralItemProps[] $sectoralItemProps
	 */
	public function addSectoralItemProps($sectoralItemProps)
	{
		$this->sectoralItemProps = $sectoralItemProps;
	}

	public function getParameters()
	{
		$params = parent::getParameters();
		if ($this->sectoralItemProps) {
			foreach ($this->sectoralItemProps as $sectoralItemProp) {
				$params['sectoral_item_props'][] = $sectoralItemProp->getParameters();
			}
		}

		return $params;
	}
}
