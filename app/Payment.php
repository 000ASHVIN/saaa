<?php

namespace App;

/**
* Payment
*/
class Payment
{
	public function __construct($data)
	{
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Determine if the Payment has been successful
	 */
	public function successful()
	{
		return preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $this->result['code']) === 1;
	}
}