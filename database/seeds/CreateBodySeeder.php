<?php

use App\Body;
use Illuminate\Database\Seeder;

class CreateBodySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Body::create([
            'logo' => '',
            'title' => 'The Institute of Certified Bookkeepers and Accountants (ICBA)',
            'email' => 'enquiries@icba.org.za',
            'address' => 'ICBA, PO Box 1178, Cape Town, 8000, South Africa',
            'description' => 'The Institute of Certified Bookkeepers and Accountants (ICBA) is a professional body for qualified finance professionals and office administrators in  southern Africa.',
            'contact_number' => '021 659 1310',
        ]);

        Body::create([
            'logo' => '',
            'title' => 'Southern African Institute for Business Accountants (SAIBA)',
            'email' => 'cpd@saiba.org.za',
            'address' => '2 Oxford Office Park, 3 Bauhinia St, Highveld Techno Park, Centurion',
            'description' => 'SAIBA is a voluntary accounting membership body with more than 6 500 members. Members fall into membership tiers, according to experience and qualifications. By joining our community of accounting professionals, members take the first step towards advancing their careers.',
            'contact_number' => ' 012 643 1800',
        ]);
//
//        Body::create([
//            'logo' => '',
//            'title' => 'South African Institute of Tax Practitioners (SAIT)',
//            'email' => 'cpd@thesait.org.za',
//            'address' => '',
//            'description' => '',
//            'contact_number' => '',
//        ]);

        Body::create([
            'logo' => '',
            'title' => 'The South African Institute of Chartered Accountants (SAICA)',
            'email' => 'membershipVerifications@accountingacademy.co.za',
            'address' => '',
            'description' => '',
            'contact_number' => '',
        ]);

        Body::create([
            'logo' => '',
            'title' => 'South African Institute of Professional Accountants (SAIPA)',
            'email' => 'membershipVerifications@accountingacademy.co.za',
            'address' => '',
            'description' => '',
            'contact_number' => '',
        ]);

        Body::create([
            'logo' => '',
            'title' => 'Other (Not one of the above)',
            'email' => 'membershipVerifications@accountingacademy.co.za',
            'address' => '',
            'description' => '',
            'contact_number' => '',
        ]);
    }
}
