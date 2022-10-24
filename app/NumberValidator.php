<?php
/**
 * Created by PhpStorm.
 * User: Tiaan
 * Date: 2018/01/12
 * Time: 14:06
 */

namespace App;


class NumberValidator
{
    /**
     * Phone Number Instance
     *
     * @var \libphonenumber\PhoneNumberUtil
     */
    protected $util;
    /**
     * Geo coder for locating numbers
     *
     * @var \libphonenumber\geocoding\PhoneNumberOfflineGeocoder
     */
    protected $geocoder;
    /**
     * Carrier detector
     *
     * @var \libphonenumber\PhoneNumberToCarrierMapper
     */
    protected $carrier;
    /**
     * NumberValidator constructor.
     */
    function __construct()
    {
        $this->util = \libphonenumber\PhoneNumberUtil::getInstance();
        $this->geocoder = \libphonenumber\geocoding\PhoneNumberOfflineGeocoder::getInstance();
        $this->carrier = \libphonenumber\PhoneNumberToCarrierMapper::getInstance();
    }

    /**
     * Validates a given number
     *
     * @param $number
     * @return bool
     * @throws \libphonenumber\NumberParseException
     */
    public function validate($number)
    {
        $number = preg_replace("/[^0-9]/","",$number);
        if(empty($number))
            return false;
        try {
            return $this->util->isValidNumber($this->util->parse($number, "ZA"));
        } catch (NumberParseException $e) {
            return false;
        }
    }

    /**
     * Format the give number into "GB" dial-out format
     *
     * @param $number
     * @return mixed
     * @throws \libphonenumber\NumberParseException
     */
    public function format($number)
    {
        $result = $this->util->formatOutOfCountryCallingNumber(
            $this->util->parse(preg_replace("/[^0-9]/","",$number), "ZA"), 'GB'
        );
        return str_replace(' ', '', $result);
    }
    /**
     * Determine the Carrier for the given number
     *
     * @param $number
     * @return string
     */
    public function carrier($number)
    {
        return $this->carrier->getNameForNumber(
            $this->util->parse(preg_replace("/[^0-9]/","",$number), "ZA"), "en"
        );
    }
    /**
     * Determine the Country for the given number
     *
     * @param $number
     * @return string
     */
    public function country($number)
    {
        return $this->geocoder->getDescriptionForNumber(
            $this->util->parse(preg_replace("/[^0-9]/","",$number), "ZA"), "en_ZA"
        );
    }
}