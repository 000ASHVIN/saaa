<?php

namespace App\Interfaces;

interface Invoiceable
{
    public function getType();
    public function getDescription();
    public function getDiscount();
}