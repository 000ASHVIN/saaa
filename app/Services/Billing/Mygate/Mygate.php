<?php 

namespace App\Services\Billing\Mygate;

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
use App\Services\Billing\Mygate\Exceptions\PaymentFailedException;

/**
 * Class Mygate
 * @package App\Services\Billing\Mygate
 */
class Mygate
{
    /**
     * All possible Error codes
     * @var array
     */
    private $errorcodes = [
        "1001" => "Gateway Required",
        "1002" => "MerchantID Required",
        "1003" => "Invalid Merchant UID",
        "1004" => "ApplicationUID Required",
        "1005" => "Invalid ApplicationUID",
        "1006" => "Action Required",
        "1007" => "Invalid Action",
        "1008" => "Invalid Transaction Index",
        "1009" => "Terminal Required",
        "1010" => "Invalid Terminal",
        "1011" => "Mode Required",
        "1012" => "Invalid Mode",
        "1013" => "MerchantReference Required",
        "1014" => "Invalid MerchantReference",
        "1015" => "Amount Required",
        "1016" => "Zero Amount",
        "1017" => "Invalid Amount",
        "1018" => "Currency Required",
        "1019" => "TransactionIndex required",
        "1020" => "CardType required",
        "1021" => "Invalid CardType",
        "1022" => "CardNumber required",
        "1023" => "Invalid Currency",
        "1024" => "Invalid CardNumber",
        "1025" => "CVV Number required",
        "1026" => "Invalid CVV Number",
        "1027" => "ExpiryMonth Required",
        "1028" => "ExpiryYear Required",
        "1029" => "Invalid ExpiryMonth",
        "1030" => "Invalid ExpiryYear",
        "1031" => "Past Expiry Date",
        "1032" => "Account Type Required",
        "1033" => "Invalid Account Type",
        "1034" => "Card Holder Required",
        "1035" => "Invalid Card Holder",
        "1036" => "Invalid CCV for this card type",
        "1037" => "Invalid Budget",
        "1038" => "Budget Period Required",
        "1039" => "Invalid Budget Period",
        "1040" => "Amount Required",
        "1041" => "Zero Amount",
        "1042" => "Invalid Amount",
        "1043" => "Invalid Authorisation",
        "1044" => "Invalid Budget",
        "1045" => "AuthorisationNumber Required",
        "1046" => "PIN Required",
        "1047" => "Invalid PIN",
        "1048" => "eCommerceIndicator Required",
        "1049" => "Invalid eCommerceIndicator",
        "1050" => "verifiedByVisaXID Required",
        "1051" => "Invalid verifiedByVisaXID",
        "1052" => "verifiedByVisaCAVV Required",
        "1053" => "Invalid verifiedByVisaCAVV",
        "1054" => "secureCodeUCAF Required",
        "1055" => "Invalid secureCodeUCAF",
        "1056" => "SSL Required",
        "1057" => "UCI Required",
        "1059" => "IP Address Required",
        "1060" => "Invalid Public IP Address",
        "1061" => "Shipping Country Code Required",
        "1062" => "Invalid Shipping Country Code Format",
        "1063" => "Invalid PurchaseItemsID",
        "1064" => "Invalid GateWayID",
        "1065" => "3D Secure failed, unsuccessful signature",
        "1066" => "3D Secure failed, unsuccessful authentication",
        "1067" => "verifiedByVisaXID Required",
        "1068" => "verifiedByVisaCAFF Required",
        "1078" => "Invalid Date From, use format: yyyy/MM/dd hh:mm",
        "1080" => "Invalid Date To, use format: yyyy/MM/dd hh:mm",
        "1081" => "Incomplete Search Criteria",
        "1090" => "Carrier Name Required",
        "1091" => "Invalid Carrier Name",
        "1092" => "Ticket Nr Required",
        "1093" => "Invalid Ticket Nr",
        "1094" => "Plan Nr Required",
        "1095" => "Invalid Plan Nr",
        "1096" => "Invoice Nr Required",
        "1097" => "Invalid Invoice Nr",
        "1098" => "Passenger Name Required",
        "1099" => "Invalid Passenger Name",
        "1100" => "Customer Ref Required",
        "1101" => "Invalid Customer Ref",
        "1102" => "Travel Agency Code Required",
        "1103" => "Invalid Travel Agency Code",
        "1104" => "Ticket Agency Name Required",
        "1105" => "Invalid Ticket Agency Name",
        "1106" => "Ticket Issue Address Required",
        "1107" => "Invalid Ticket Issue Address",
        "1108" => "Date Ticket Issue Required",
        "1109" => "Invalid Date Ticket Issue",
        "1110" => "Amount Total Fare Required",
        "1111" => "Invalid Amount Total Fare",
        "1112" => "Amount Total Fees Required",
        "1113" => "Invalid Amount Total Fees",
        "1114" => "Amount Total Taxes Required",
        "1115" => "Invalid Amount Total Taxes",
        "1116" => "Amount Original Invoice Required",
        "1117" => "Invalid Amount Original Invoice",
        "1118" => "Original Curency Code Required",
        "1119" => "Invalid Original Currency Code",
        "1120" => "Leg Nr Required",
        "1121" => "Invalid Leg Nr",
        "1122" => "Leg Carrier Code Required",
        "1123" => "Invalid Leg Carrier Code",
        "1124" => "Leg Flight Nr Required",
        "1125" => "Invalid Leg Flight Nr",
        "1126" => "Leg Departure Airport Required",
        "1127" => "Invalid Leg Departure Airport",
        "1128" => "Leg Stopover Code Required",
        "1129" => "Invalid Leg Stopover Code",
        "1130" => "Leg Destination Code Required",
        "1131" => "Invalid Leg Destination Code",
        "1132" => "Leg Date Of Travel Required",
        "1133" => "Invalid Leg Date Of Travel",
        "1134" => "Leg Departure Time Required",
        "1135" => "Invalid Leg Departure Time",
        "1136" => "Leg Departure Time Segment Code Required",
        "1137" => "Invalid Leg Departure Time Segment Code",
        "1138" => "Leg Arrival Time Required",
        "1139" => "Invalid Leg Arrival Time",
        "1140" => "Leg Arrival Time Segment Code Required",
        "1141" => "Invalid Leg Arrival Time Segment Code",
        "1142" => "Leg Class Of Travel Required",
        "1143" => "Invalid Leg Class Of Travel",
        "1144" => "Leg Coupon Nr Required",
        "1145" => "Invalid Leg Coupon Nr",
        "1146" => "Leg Conjunction Ticket Nr Required",
        "1147" => "Invalid Leg Conjunction Ticket Nr",
        "1148" => "Leg Exchange Ticket Nr Required",
        "1149" => "Invalid Leg Exchange Ticket Nr",
        "1150" => "Leg Fare Basis Code Required",
        "1151" => "Invalid Leg Fare Basis Code",
        "1152" => "Leg Amount Fare Required",
        "1153" => "Invalid Leg Amount Fare",
        "1154" => "Leg Amount Fees Required",
        "1155" => "Invalid Leg Amount Fees",
        "1156" => "Leg Amount Taxes Required",
        "1157" => "Invalid Leg Amount Taxes",
        "1158" => "Leg Amount Departure Tax Required",
        "1158" => "Invalid Leg Amount Departure Tax",
        "1160" => "Leg Endorsements Or Restrictions Required",
        "1161" => "Invalid Leg Endorsements Or Restrictions",
        "2001" => "Invalid Gateway",
        "2002" => "MerchantID is cancelled.",
        "2003" => "ApplicationID does not exist",
        "2004" => "TransactionIndex not found",
        "2005" => "Duplicate Merchant Reference",
        "2006" => "Can’t Credit without Debit",
        "2007" => "ReverseAuthorisation Already Performed",
        "2008" => "Debit Already Performed",
        "2009" => "Credit Already Performed",
        "2010" => "Authorisation Expected",
        "2011" => "Debit Already performed",
        "2012" => "Authorisation Already performed",
        "2013" => "Budget must preceed transaction",
        "2014" => "Authorise must preceed transaction",
        "2015" => "Authorise must preceed transaction",
        "2016" => "Refund must preceed transaction",
        "2017" => "Refund must preceed transaction",
        "2018" => "Authorise must preceed transaction",
        "2019" => "Refund must preceed transaction",
        "2020" => "Incorrect Relationship",
        "2021" => "Invalid Shipping Country Code",
        "2022" => "Purchase must preceed transaction",
        "2023" => "Purchase Reversal must preceed transaction",
        "2024" => "Purchase must preceed transaction",
        "2025" => "Credit must preceed transaction",
        "2050" => "Incorrect Decimal Length",
        "4001" => "Mode Denied",
        "4002" => "Invalid Credit Card",
        "4003" => "Merchant Inactive",
        "4004" => "Merchant Suspended",
        "4005" => "Merchant Removed",
        "4006" => "Application Inactive",
        "4007" => "Application Suspended",
        "4008" => "Application Removed",
        "4009" => "Source IP Address Invalid",
        "4010" => "Gateway Inactive",
        "4011" => "Gateway Obsolete",
        "4012" => "Currency not allowed",
        "4013" => "Invalid Currency",
        "4014" => "Transaction Limit Exceeded",
        "4015" => "Cumulative Transaction Limit Exceeded",
        "4016" => "CardType not allowed",
        "4017" => "AccountType not allowed",
        "4018" => "Budget not allowed",
        "4019" => "eCommerceIndicator not allowed",
        "5001" => "Connection error to bank",
        "5002" => "Declined from the bank",
        "5003" => "Unexpected Error",
        "6001" => "Card Number Response Blacklisted",
        "6002" => "Country Code Response Blacklisted",
        "6003" => "IP Address Blacklisted",
        "6004" => "UCI Blacklisted",
        "6005" => "GIR Check Failed",
        "6006" => "IVS Check Failed",
        "6007" => "RFI Check Failed",
        "6008" => "RSI Check Failed",
        "6009" => "UCI Check Failed",
        "6999" => "Fraud Module: Error encountered",
        "7001" => "Client Token Required",
        "7002" => "Invalid Client Token",
        "7003" => "Tokenization Record Not Found",
        "7004" => "Token Deregistered",
        "8001" => "Mode was ignored",
        "8002" => "Amount was ignored",
        "8003" => "CCType was ignore",
        "8004" => "CCNumber was ignored",
        "8005" => "CCVNumber was ignored",
        "8006" => "ExpiryMonth was ignored",
        "8007" => "ExpiryYear was ignored",
        "8008" => "Cumulative Transaction Limit Almost Exceeded",
        "8009" => "Transaction Limit Exceeded",
        "8010" => "Cumulative Transaction Limit Exceeded",
        "8011" => "Transaction Index was ignored",
        "8012" => "Currency was ignored",
        "8013" => "Account Type was ignored",
        "8014" => "Card Holder was ignored",
        "8015" => "Budget was ignored",
        "8016" => "Budget Period was ignored",
        "8017" => "Cashback Amount was ignored",
        "8018" => "Authorisation was ignored",
        "8019" => "PIN was ignored",
        "8020" => "Terminal was ignored",
        "8021" => "ClientReference was ignored",
        "8022" => "eCommerceIndicator was ignored",
        "8023" => "verifiedByVisaXID was ignored",
        "8024" => "verifiedByVisaCAVV was ignored",
        "8025" => "secureCodeUCAF was ignored",
        "8026" => "UCI was ignored",
        "8027" => "IP Address was ignored",
        "8028" => "Shipping Country Code was ignored",
        "8029" => "Flagged for Card Number Blacklist",
        "8030" => "Flagged for Country Code Blacklist",
        "8031" => "Flagged for IP Address Blacklist",
        "8032" => "Flagged for UCI Blacklist",
        "8033" => "Flagged for GIR",
        "8034" => "Flagged for IVS",
        "8035" => "Flagged for RFI",
        "8036" => "Flagged for RSI",
        "8037" => "Flagged for UCI",
        "8038" => "Card Number Blacklist not performed",
        "8039" => "Country Code Blacklist not performed",
        "8040" => "IP Address Blacklist not performed",
        "8041" => "IP Address Blacklist not performed",
        "8042" => "GIR not performed",
        "8043" => "IVS not performed",
        "8044" => "RFI not performed",
        "8045" => "RSI not performed",
        "8046" => "UCI not performed",
        "8047" => "PurchaseItemsID Was Ignored",
        "9001" => "Unexpected Error",
        "9002" => "Request Not Implemented",
        "9003" => "Card Type Not Implemented",
        "9004" => "Fraud Status Unknown",
        "9005" => "Fraud Result Unknown",
        "9998" => "Consummation Error"
    ];

    /**
     * @var mixed
     */
    protected $merchantId;
    /**
     * @var mixed
     */
    protected $appId;
    /**
     * @var
     */
    protected $secureId;
    /**
     * @var mixed
     */
    protected $gateway;
    /**
     * @var mixed
     */
    protected $currency;
    /**
     * @var mixed
     */
    protected $mode;
    /**
     * @var mixed
     */
    protected $url;
    /**
     * @var mixed
     */
    protected $threeUrl;
    /**
     * @var
     */
    protected $transactionId;
    /**
     * @var
     */
    protected $PAReqMsg;
    /**
     * @var
     */
    protected $AcsUrl;
    /**
     * @var string
     */
    protected $BrowserHeader;
    /**
     * @var string
     */
    protected $UserAgent;
    /**
     * @var
     */
    public $error;
    /**
     * @var
     */
    public $results;

    /**
     * Mygate constructor.
     */
    public function __construct()
    {        
        $this->merchantId = env('MERCHANT_ID');
        $this->appId = env('MERCHANT_APPID');
        $this->gateway = env('MERCHANT_GATEWAY');
        $this->currency = env('MERCHANT_CURRENCY');
        $this->mode = env('MERCHANT_MODE', '0');
        $this->url = env('MERCHANT_URL');
        $this->threeUrl = env('MERCHANT_THREEURL');
        $this->BrowserHeader = (isset($_SERVER['HTTP_USER_AGENT'])) ?: 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4';
        $this->UserAgent = (isset($_SERVER['HTTP_ACCEPT'])) ?: 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
    }

    /**
     * @param $cardNumber
     * @param $expYear
     * @param $expMonth
     * @param $amount
     * @param $order_number
     * @param $order_description
     * @return mixed
     */
    public function checkThreeDs($cardNumber, $expYear, $expMonth, $amount, $order_number, $order_description)
    {
        $panExp = $expYear . $expMonth;

        $data = [
            'MerchantID' => $this->merchantId,
            'ApplicationID' => $this->appId,
            'Mode' => $this->mode,
            'PAN' => $cardNumber,
            'PANExpr' => $panExp,
            'PurchaseAmount' => $amount,
            'UserAgent' => $this->UserAgent,
            'BrowserHeader' => $this->BrowserHeader,
            'OrderNumber' => $order_number,
            'OrderDesc' => $order_description,
            'Recurring' => 'N',
            'RecurringFrequency' => ''
        ];

        SoapWrapper::add(function ($service) {
            $service->name('lookup')
                ->wsdl(env('MERCHANT_THREEURL'));
        });

        SoapWrapper::service('lookup', function ($service) use ($data) {
            $this->processResult($service->call('lookup', $data));
        });

        return $this->results;
    }

    /**
     * @param $TransactionId
     * @param $PAResPayload
     * @return bool
     */
    public function authenticate($TransactionId, $PAResPayload)
    {   
        $this->transactionId = $TransactionId;
        $this->PAReqMsg = $PAResPayload;

        $data = [ 'TransactionIndex' => $TransactionId, 'PAResPayload' => $PAResPayload ];

        SoapWrapper::add(function ($service) {
            $service->name('authenticate')
                ->wsdl(env('MERCHANT_THREEURL'));
        });

        SoapWrapper::service('authenticate', function ($service) use ($data) {
            $this->processResult($service->call('authenticate', $data));
        });

        if((int)$this->results['Result'] == 0)
            return true;

        return false;
    }

    /**
     * @param MyGateCard $card
     * @param $amount
     * @param $ref
     * @param null $transactionId
     * @return bool
     */
    public function charge(MyGateCard $card, $amount, $ref, $transactionId = null)
    {
        if ($transactionId) {
            $this->transactionId = $transactionId;
        }
        
        $this->process($card, $amount, $ref);
        $this->settle();
    }

    /**
     * @param MyGateCard $card
     * @param $amount
     * @param $ref
     * @return bool
     */
    protected function process(MyGateCard $card, $amount, $ref)
    {
        $data = [
            'GatewayID' => $this->gateway,
            'MerchantID' => $this->merchantId,
            'ApplicationID' => $this->appId,
            'Action' => '1',
            'TransactionIndex' => $this->transactionId,
            'Terminal' => 'Website',
            'Mode' => $this->mode,
            'MerchantReference' => $ref,
            'Amount' => $amount,
            'Currency' => $this->currency,
            'CashBackAmount' => '',
            'CardType' => $card->getCardType(),
            'AccountType' => '',
            'CardNumber' => $card->getCardNumber(),
            'CardHolder' => $card->getCardName(),
            'CCVNumber' => $card->getCardCvv(),
            'ExpiryMonth' => $card->getCardExpMonth(),
            'ExpiryYear' => $card->getCardExpYear()
        ];

        SoapWrapper::add(function ($service) {
            $service->name('charge')
                ->wsdl(env('MERCHANT_URL'));
        });

        SoapWrapper::service('charge', function ($service) use ($data) {
            $this->processResult($service->call('fProcess', $data));
            if (isset($this->results['TransactionIndex']))
                $this->transactionId = $this->results['TransactionIndex'];
        });

        if((int)$this->results['Result'] == 0)
            return true;

        $this->getError($this->results['Error']);
    }

    /**
     * @return bool
     */
    private function settle()
    {
        $data = [
            'GatewayID' => $this->gateway,
            'MerchantID' => $this->merchantId,
            'ApplicationID' => $this->appId,
            'Action' => '3',
            'TransactionIndex' => $this->transactionId,
        ];

        SoapWrapper::add(function ($service) {
            $service->name('settle')
                ->wsdl(env('MERCHANT_URL'));
        });
        
        SoapWrapper::service('settle', function ($service) use ($data) {
            $this->processResult($service->call('fProcess', $data));
        });

        if((int)$this->results['Result'] == 0)
            return true;

        $this->getError($this->results['Error']);

    }

    /**
     * @param array $results
     * @return array
     */
    private function processResult(array $results)
    {
        $response = [];

        foreach($results as $result) {
            $toadd = explode('||', $result);
            $response[$toadd[0]] = $toadd[1];
        }

        return $this->results = $response;
    }

    /**
     * @param $error
     * @throws PaymentFailedException
     */
    private function getError($error)
    {
        if(array_key_exists($error, $this->errorcodes))
        {
            throw new PaymentFailedException($this->errorcodes[$error], 404);
        }

        throw new PaymentFailedException("Unknown Error Occurred, please try again.", 404);
    }
}