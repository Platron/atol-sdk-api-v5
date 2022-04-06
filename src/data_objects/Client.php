<?php

namespace Platron\AtolV5\data_objects;

class Client extends BaseDataObject
{
	/** @var int $phone */
	protected $phone;
	/** @var string $email */
	protected $email;
	/** @var string $name */
	protected $name;
	/** @var string $inn */
	protected $inn;
	/** @var string $birthdate */
	protected $birthdate;
	/** @var string $citizenship */
	protected $citizenship;
	/** @var string $document_code */
	protected $document_code;
	/** @var  string $document_data */
	protected $document_data;
	/** @var string $address */
	protected $address;

	/**
	 * @param string $email
	 */
	public function addEmail($email)
	{
		$this->email = (string)$email;
	}

	/**
	 * @param int $phone Номер телефона в международном формате
	 */
	public function addPhone($phone)
	{
		$this->phone = '+' . (string)$phone;
	}

	/**
	 * @param string $name
	 */
	public function addName($name)
	{
		$this->name = (string)$name;
	}

	/**
	 * @param string $inn
	 */
	public function addInn($inn)
	{
		$this->inn = (string)$inn;
	}

	/**
	 * @param string $birthdate
	 */
	public function addBirthdate($birthdate)
	{
		$this->birthdate = (string)$birthdate;
	}

	/**
	 * @param string $citizenship
	 */
	public function addCitizenship($citizenship)
	{
		$this->citizenship = (string)$citizenship;
	}

	/**
	 * @param string $documentCode
	 */
	public function addDocumentCode($documentCode)
	{
		$this->document_code = (string)$documentCode;
	}

	/**
	 * @param string $documentData
	 */
	public function addDocumentData($documentData)
	{
		$this->document_data = (string)$documentData;
	}

	/**
	 * @param string $address
	 */
	public function addAddress($address)
	{
		$this->address = (string)$address;
	}


}