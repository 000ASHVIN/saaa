<?php

use App\InstallmentConfig;
use App\Subscriptions\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PlansInstallmentConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $monthlyInstallmentConfig = InstallmentConfig::create([
            'interval' => 1,
            'interval_unit' => 'month',
            'total_number' => 12,
            'grace_period_days' => 30
        ]);
        $yearlyInstallmentConfig = InstallmentConfig::create([
            'interval' => 1,
            'interval_unit' => 'year',
            'total_number' => 1,
            'grace_period_days' => 14
        ]);

        $monthlyAccountingOnly = Plan::where('slug', 'monthly-accounting-only')->first();
        $monthlyAccountingOnly->installmentConfig()->save($monthlyInstallmentConfig);

        $yearlyAccountingOnly = Plan::where('slug', 'yearly-accounting-only')->first();
        $yearlyAccountingOnly->installmentConfig()->save($yearlyInstallmentConfig);

        $monthlyAccountingAndTax = Plan::where('slug', 'monthly-accounting-and-tax')->first();
        $monthlyAccountingAndTax->installmentConfig()->save($monthlyInstallmentConfig);

        $yearlyAccountingAndTax = Plan::where('slug', 'yearly-accounting-and-tax')->first();
        $yearlyAccountingAndTax->installmentConfig()->save($yearlyInstallmentConfig);

        Model::reguard();
    }
}
