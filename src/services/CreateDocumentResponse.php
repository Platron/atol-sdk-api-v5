<?php

namespace Platron\AtolV5\services;

abstract class CreateDocumentResponse extends BaseServiceResponse
{
	/** @var string Уникальный идентификатор */
	public $uuid;

	/** @var string */
	public $status;
}