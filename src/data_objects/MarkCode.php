<?php

namespace Platron\AtolV5\data_objects;
use Platron\AtolV5\handbooks\MarkCodeTypes;

class MarkCode extends BaseDataObject
{
	/** @var string */
	protected $type;
	/** @var string */
	protected $value;

	/**
	 * MarkCode constructor.
	 * @param MarkCodeTypes $markCodeType
	 * @param string $value
	 */
	public function __construct(MarkCodeTypes $markCodeType, string $value)
	{
		$this->value = (string)$value;
		$this->type = $markCodeType->getValue();
	}
	public function getParameters() {
		$field = [];
		$field[$this->type] = $this->value;
		return $field;
	}
}