<?php

namespace App\Subscriptions\Contracts;

interface FeatureInterface {

	public function plan();
    public function usage();
    
}