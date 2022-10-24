<?php

namespace App\Repositories\Plan;


use App\Subscriptions\Plan;

class PlanRepository
{
    private $plan;
    public function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }
}