<?php 

namespace App\Services\Billing\Mygate\Exceptions;

use Exception;

class PaymentFailedException extends Exception
{
    protected $message;
	protected $code;

    public function __construct($message, $code)
    {
        $this->message = $message;
		$this->code = $code;
    }
}