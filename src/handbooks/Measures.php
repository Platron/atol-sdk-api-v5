<?php


namespace Platron\AtolV5\handbooks;

use MyCLabs\Enum\Enum;

class Measures extends Enum
{

	const
		ONE = 0,
		GR = 10,
		KGR = 11,
		TON = 12,
		CM = 20,
		DM = 21,
		M = 22,
		CM2 = 30,
		DM2 = 31,
		M2 = 32,
		MILLILITRE = 40,
		LITRE = 41,
		M3 = 42,
		KWH = 50,
		GCAL = 51,
		DAY = 70,
		HOUR = 71,
		MINUTE = 72,
		SECOND = 73,
		KB = 80,
		MB = 81,
		GB = 82,
		TB = 83,
		OTHER = 255;
}
