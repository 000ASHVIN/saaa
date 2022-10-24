<?php

namespace App;

use GuzzleHttp\Client as Guzzle;

/**
* Peach
*/
class Peach
{
	private $client;

	public function __construct(Guzzle $client)
	{
		$this->client = $client;
	}

	public function autorize($number, $holder, $exp_month, $exp_year, $cvv, $reference, $url = null)
	{	
		$token = config('services.peach.token');
		$headers = [
			'Authorization' => 'Bearer ' . $token,        
			'Content-Type'        => 'application/x-www-form-urlencoded',
		];		
		try {
			$response = $this->client->post(config('services.peach.endpoint') . '/v1/payments', [
				'form_params' => [
					'entityId' => config('services.peach.entityId'),
					'merchantTransactionId' => $reference,
					'amount' => number_format(1, 2),
			    	'currency' => 'ZAR',
			    	'paymentBrand' => $this->getCardType($number),
			    	'paymentType' => 'PA',
					'card.number' => $number,
			    	'card.holder' => $holder,
			    	'card.expiryMonth' => $exp_month,
			    	'card.expiryYear' => $exp_year,
			    	'card.cvv' => $cvv,
			    	'createRegistration' => true,
			    	'recurringType' => 'INITIAL',
			    	'shopperResultUrl' => config('app.url') . $url ?: '/return',			    	
				],
				'headers' => $headers				
			]);
			\Log::info($response->getBody());
			return json_decode($response->getBody(), true);
		} catch (\GuzzleHttp\Exception\ClientException $e) {
	    	return json_decode($e->getResponse()->getBody()->getContents(), true);
	    }
	}

	
	public function autorizeandFetch($number, $holder, $exp_month, $exp_year, $cvv, $reference, $url = null,$amount)
	{	
		$token = config('services.peach.token');
		$headers = [
			'Authorization' => 'Bearer ' . $token,        
			'Content-Type'        => 'application/x-www-form-urlencoded',
		];	
		try {
			$response = $this->client->post(config('services.peach.endpoint') . '/v1/payments', [
				'form_params' => [
					'entityId' => config('services.peach.entityId'),
					'merchantTransactionId' => $reference,
					'amount' => number_format($amount, 2, '.', ''),
			    	'currency' => 'ZAR',
			    	'paymentBrand' => $this->getCardType($number),
			    	'paymentType' => 'DB',
					'card.number' => $number,
			    	'card.holder' => $holder,
			    	'card.expiryMonth' => $exp_month,
			    	'card.expiryYear' => $exp_year,
			    	'card.cvv' => $cvv,
			    	'createRegistration' => true,
			    	'shopperResultUrl' => config('app.url') . $url ?: '/return',			    	
				],
				'headers' => $headers
			]);
			\Log::info($response->getBody());
			return json_decode($response->getBody(), true);
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$json = $e->getResponse()->getBody()->getContents();
			\Log::info('PAYMENT_ERROR: '.$json);
	    	return json_decode($json, true);
	    }
	}

	public function fetchPayment($id)
	{
		$token = config('services.peach.token');
		$headers = [
			'Authorization' => 'Bearer ' . $token,        
			'Content-Type'        => 'application/x-www-form-urlencoded',
		];
		$response = $this->client->get(config('services.peach.endpoint') . "/v1/payments/{$id}", [
			'query' => [
				'entityId' => config('services.peach.entityId')
			],
			'headers' => $headers
		]);
		\Log::info($response->getBody());
		$data = json_decode($response->getBody(), true);

		return new Payment($data);
	}

	public function charge($token, $amount, $reference, $description = null)
	{
		$authtoken = config('services.peach.token');
		$headers = [
			'Authorization' => 'Bearer ' . $authtoken,        
			'Content-Type'        => 'application/x-www-form-urlencoded',
		];
		try {
			$response = $this->client->post(config('services.peach.endpoint') . "/v1/registrations/{$token}/payments", [
			'form_params' => [
				'entityId' => config('services.peach.recurringId'),
				'merchantTransactionId' => $reference,
				'amount' => number_format($amount, 2, '.', ''),
				'currency' => 'ZAR',
				'paymentType' => 'DB',
				'recurringType' => 'REPEATED',
                'merchantInvoiceId' => str_limit($reference, 255),
                'descriptor' => $description ?: str_limit($description, 127)
			],
			'headers' => $headers
		]);

		$data = json_decode($response->getBody(), true);
		\Log::info($data);
		return $data;

		} catch (\GuzzleHttp\Exception\ClientException $e) {
	    	return json_decode($e->getResponse()->getBody()->getContents(), true);
	    }
	}

	public function deleteToken($token)
	{
		try {
			$response = $this->client->delete(config('services.peach.endpoint') . "/v1/registrations/{$token}", [
			'query' => [
				'authentication.userId' => config('services.peach.user_id'),
				'authentication.password' => config('services.peach.password'),
				'authentication.entityId' => config('services.peach.entityId'),
			]
		]);

		$data = json_decode($response->getBody(), true);

		return $data;

		} catch (\GuzzleHttp\Exception\ClientException $e) {
	    	return json_decode($e->getResponse()->getBody()->getContents(), true);
	    }
	}

	protected function getCardType($number) {
	    $re = [
	        "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
	        "mastercard" => "/^5[1-5][0-9]{14}$/"
	    ];

	    if (preg_match($re['visa'],$number)) {
	        return 'VISA';
	    }

	    else if (preg_match($re['mastercard'],$number)) {
	        return 'MASTER';
	    }
	 }
}