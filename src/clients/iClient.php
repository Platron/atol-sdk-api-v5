<?php

namespace Platron\AtolV5\clients;

use Platron\AtolV5\services\BaseServiceRequest;
use stdClass;
interface iClient
{

	/**
	 * Послать запрос
	 * @param BaseServiceRequest $service
	 * @return stdClass
	 */
	public function sendRequest(BaseServiceRequest $service);
}
