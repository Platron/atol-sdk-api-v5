<?php

namespace Platron\AtolV5\data_objects;
use Platron\AtolV5\handbooks\MarkCodeTypes;

class MarkCode extends BaseDataObject
{

	/** @var string */
	protected $unknown;
	/** @var string */
	protected $ean8;
	/** @var string */
	protected $ean13;
	/** @var string */
	protected $itf14;
	/** @var string */
	protected $gs1m;
	/** @var string */
	protected $short;
	/** @var string */
	protected $fur;
	/** @var string */
	protected $egais20;
	/** @var string */
	protected $egais30;

	/**
	 * @param string $unknown
	 */
	public function addUnknown($unknown)
	{
		$this->unknow = (string)$unknown;
	}

	/**
	 * @param string $ean8
	 */
	public function addEan8($ean8)
	{
		$this->ean8 = (string)$ean8;
	}

	/**
	 * @param string $ean13
	 */
	public function addEean13($ean13)
	{
		$this->ean13 = (string)$ean13;
	}

	/**
	 * @param string $itf14
	 */
	public function addItf14($itf14)
	{
		$this->itf14 = (string)$itf14;
	}

	/**
	 * @param string $gs1m
	 */
	public function addGs1m($gs1m)
	{
		$this->gs1m = (string)$gs1m;
	}

	/**
	 * @param string $short
	 */
	public function addShort($short)
	{
		$this->short = (string)$short;
	}

	/**
	 * @param string $fur
	 */
	public function addFur($fur)
	{
		$this->fur = (string)$fur;
	}

	/**
	 * @param string $egais20
	 */
	public function addEgais20($egais20)
	{
		$this->egais20 = (string)$egais20;
	}

	/**
	 * @param string $egais30
	 */
	public function addEgais30($egais30)
	{
		$this->egais30 = (string)$egais30;
	}
}