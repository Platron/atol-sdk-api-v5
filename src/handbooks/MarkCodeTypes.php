<?php

namespace Platron\AtolV5\handbooks;

use MyCLabs\Enum\Enum;

class MarkCodeTypes extends Enum{

	const
		UNKNOW = "unknown",
		EAN8 = "ean8",
		EAN13 = "ean13",
		ITF14 = "itf14",
		GS1M = "gs1m",
		SHORT = "short",
		FUR = "fur",
		EGAIS20 = "egais20",
		EGAIS30 = "egais30"
	;

}
