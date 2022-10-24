<?php 

namespace App\Services\Billing\Mygate;

class MyGateCard
{
    protected $cardName;
    protected $cardExpMonth;
    protected $cardExpYear;
    protected $cardNumber;
    protected $cardCvv;
    protected $cardType;

    public function __construct($cardName, $cardExpMonth, $cardExpYear, $cardNumber, $cardCvv)
    {
        $this->cardName = $cardName;
        $this->cardExpMonth = $cardExpMonth;
        $this->cardExpYear = 20 . $cardExpYear;
        $this->cardNumber = $cardNumber;
        $this->cardCvv = $cardCvv;
        $this->setCardType();
    }

    /**
     * Set the Card Type
     *
     * 1 American Express
     * 2 Discover
     * 3 MasterCard
     * 4 Visa
     * 5 Diners
     */
    protected function setCardType()
    {
        $card = preg_replace('/[^\d]/', '', $this->cardNumber);

        if (preg_match('/^3[47][0-9]{13}$/', $card)) {
            $this->cardType = '1';
        } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $card)) {
            $this->cardType = '5';
        } elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/', $card)) {
            $this->cardType = '2';
        } elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $card)) {
            $this->cardType = 'JCB';
        } elseif (preg_match('/^5[1-5][0-9]{14}$/', $card)) {
            $this->cardType = '3';
        } elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $card)) {
            $this->cardType = '4';
        } else {
            $this->cardType = 'Unknown';
        }
    }

    /**
     * Returns the Card type
     *
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Returns the Card CVV
     *
     * @return mixed
     */
    public function getCardCvv()
    {
        return $this->cardCvv;
    }

    /**
     * Return the Card number
     *
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Return the Card expiry year
     *
     * @return mixed
     */
    public function getCardExpYear()
    {
        return $this->cardExpYear;
    }

    /**
     * Return the Card expiry month
     *
     * @return mixed
     */
    public function getCardExpMonth()
    {
        return $this->cardExpMonth;
    }

    /**
     * Return the Card name
     *
     * @return mixed
     */
    public function getCardName()
    {
        return $this->cardName;
    }
}