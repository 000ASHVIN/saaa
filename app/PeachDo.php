<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Client;

class PeachDo {

	private $client;

	function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function validate($account_number, $branch_code)
	{
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><APICDVRequest></APICDVRequest>');

		$header = $xml->addChild('Header');
		$header->addChild('PsVer', '2.0.1');
		$header->addChild('Client', 'PEA001');
		$header->addChild('Service', 'CDV');
		$header->addChild('Reference', Carbon::now()->toFormattedDateString());

		$records = $xml->addChild('Records');

		$contents = $records->addChild('FileContents');
		$contents->addChild('AccountNumber', $account_number);
		$contents->addChild('BranchCode', $branch_code);

		$totals = $xml->addChild('Totals');
		$totals->addChild('Records', '1');
    	$totals->addChild('BranchHash', $branch_code);
    	$totals->addChild('AccountHash', $account_number);

    	$xml = $xml->asXML();

		$response = $this->client->request('POST', 'https://test.peachpay.co.za/API/CDV?key=63d0e7c7-52f9-4e7f-951a-5645e0386bd2', [
            'form_params' => [
            	'request' => $xml
            ]
        ]);

        $simple = simplexml_load_string($response->getBody()->getContents());
 		$arr = json_decode( json_encode($simple) , 1);

 		return $arr['CDVResults']['Result'];
	}

	public function load($duedate, $debits)
	{
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><APIDebitOrdersRequest></APIDebitOrdersRequest>');

		$header = $xml->addChild('Header');
		$header->addChild('PsVer', '2.0.1');
		$header->addChild('Client', env('PEACH_CID'));
		$header->addChild('Service', 'DebitOrder');
		$header->addChild('ServiceType', 'SDV');
		$header->addChild('Duedate', $duedate);
		$header->addChild('CallBackUrl', url('/').'/peach/notify');
		$header->addChild('Reference', Carbon::now()->toFormattedDateString());

		$orders = $xml->addChild('DebitOrders');

		$records = 0;
		$amount = 0;
		$branchHash = 0;
		$accountHash = 0;

		foreach ($debits as $debit) {
		    $records += 1;
		    $amount += $debit['amount'];
		    $branchHash += $debit['branch_code'];
		    $accountHash += $debit['account_number'];

            $dbt = $orders->addChild('FileContents');
            $dbt->addChild('FirstNames', $debit['first_name']);
            $dbt->addChild('Surname', $debit['last_name']);
            $dbt->addChild('BranchCode', $debit['branch_code']);
            $dbt->addChild('AccountNumber', $debit['account_number']);
            $dbt->addChild('FileAmount', $debit['amount']);
            $dbt->addChild('AmountMultiplier', '1');
            $dbt->addChild('Reference', $debit['reference']);
        }

		$totals = $xml->addChild('Totals');
		$totals->addChild('Records', $records);
    	$totals->addChild('Amount', $amount);
    	$totals->addChild('BranchHash', $branchHash);
    	$totals->addChild('AccountHash', $accountHash);

		$xml = $xml->asXML();

		$response = $this->client->request('POST', env('PEACH_DO_POINT'), [
            'form_params' => [
            	'request' => $xml
            ]
        ]);

        $simple = simplexml_load_string($response->getBody()->getContents());
 		
 		$arr = json_decode( json_encode($simple) , 1);

 		return $arr;
	}
}