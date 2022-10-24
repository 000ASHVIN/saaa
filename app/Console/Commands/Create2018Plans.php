<?php

namespace App\Console\Commands;

use App\Profession\Profession;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Plan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Create2018Plans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:2018Plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We will now create the 2018 Plans';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Step 1, Suspend all CPD Packages.
        $this->info("We are setting all current plans to inactive!");
        $plans = Plan::all();

        // Create all the new Professions
        DB::transaction(function () use($plans) {

            foreach ($plans as $plan){
                $this->info("Suspending ".$plan->name);
                $plan->suspend();
            }

            // Truncate all the professions.
            Profession::truncate();

            // New Features
            $features = [
                'Auditing 2018',
                'IFRS 2018',
                'IFRS for SME 2018',
                'Taxation 2018',
                'CIPC Notices 2018',
                'SARS Operations 2018',
                'Labour 2018',
                'Other Legilation updates 2018',
                'Annual IFRS Update 2018',
                'Annual IAS Update 2018',
                'Ethics, independence and NOCLAR 2018',
                'Year-end Tax Update  2018',
                'Trusts and Estates 2018',
                'SARS and IRBA Compliance 2018',
                'Annual IFRS for SME update 2018',
                'Reporting engagements Accounting officer, review and compilations 2018',
                'Year-end Tax Update 2018',
                'SARS and CIPC Compliance  2018',
                'Reporting engagements Accounting officer review and compilations 2018',
                'Companies Act and directors duties 2018',
                'Tax and IFRS for SME Reconciliations 2018',
                'Bookkeeping tips and tricks  2018',
                'Using sofware tools for workflow management 2018',
                'Preparing Management Accounts 2018',
                'Drafting Budgets and cash-flow for business 2018',
                'Working capital management 2018',
                'Annual Paye and VAT Update 2018',
                'SARS issues and Tax Administration 2018',
                'Annual legislation update affecting boards 2018',
                'Governance Accounting and tax issues for company secretaries 2018',
                'Board management and Directors duties 2018',
                'Company secretarial work 2018',
                'Corporate governance and King 4 2018',
                'Accounting Officer Engagements 2018',
                'CIPC Update 2018',
                'Trust Law Update 2018',
                'Business Rescue 2018',
                'IFRS for SMEs Update 2018',
                'Preparing Financial Statements for IFRS for SMEs 2018',
                'Budget Tax Update 2018',
                'Tax Administration Act SARS Objections, Appeals Dispute Resolution 2018',
                'Tax Planning for Trusts Deceased Estates 2018',
                'Specialised VAT Issues 2018',
                'Entrepreneurial Medium Enterprises 2018',
            ];

            foreach ($features as $feature){
                Feature::create([
                    'name' => $feature,
                    'value' => '1'
                ]);
            }

            // Create new Essentials Plan
            $essentials = Plan::create([
                'name' => 'Monthly Legislation Update',
                'description' => 'Essential <br> <small>KEEPING YOU UPDATED WITH THE LATEST CHANGES</small>',
                'price' => '250',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $essentials_yearly = Plan::create([
                'name' => 'Monthly Legislation Update',
                'description' => 'Essential <br> <small>KEEPING YOU UPDATED WITH THE LATEST CHANGES</small>',
                'price' => '2700',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);


            // Create new Profession
            $charted_accountant = Profession::create([
                'title' => 'Chartered Accountant',
                'slug' => 'chartered-accountant',
                'is_active' => true,
                'description' => '
                    <p>
                        The ESSENTIAL PLUS programme for Chartered Accountant includes the following events. Events can be attended either via seminar or webinar. Limited space at seminars so reserve your seat early. Webinars are recorded in studio and can be watched live or as a recording. Recordings are loaded in you online profile:
                    </p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td>Event</td>
                            <td>Month</td>
                            <td>CPD points</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Monthly Compliance and Legislation Update </td>
                            <td>Every Month</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>Ethics, independence and NOCLAR</td>
                            <td>February</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Annual IFRS Update </td>
                            <td>March</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Annual IAS Update</td>
                            <td>May</td>
                            <td>Annual IAS Update 	May	4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Trusts and Deceased Estates</td>
                            <td>September</td>
                            <td>Trusts and Deceased Estates	September	4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>SARS and IRBA Compliance </td>
                            <td>October</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Year-end Tax Update</td>
                            <td>November</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        </tbody>
                    </table>                
                ',
            ]);

            // Create new Plan
            $charted_accountant_plan = Plan::create([
                'name' => 'Chartered Accountant',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '445',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            // Create new Plan
            $charted_accountant_plan_yearly = Plan::create([
                'name' => 'Chartered Accountant',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '4806',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $charted_accountant->plans()->save($essentials);
            $charted_accountant->plans()->save($essentials_yearly);
            $charted_accountant->plans()->save($charted_accountant_plan);
            $charted_accountant->plans()->save($charted_accountant_plan_yearly);

            $professional_accountant = Profession::create([
                'title' => 'Professional Accountant',
                'slug' => 'professional-accountant',
                'is_active' => true,
                'description' => '
                    <p>
                        The ESSENTIAL PLUS programme for Professional Accountant includes the following events. Events can be attended either via seminar or webinar. Limited space at seminars so reserve your seat early. Webinars are recorded in studio and can be watched live or as a recording. Recordings are loaded in you online profile:
                    </p>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td>Event</td>
                            <td>Month</td>
                            <td>CPD points</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Monthly Compliance and Legislation Update </td>
                            <td>Every Month</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>Ethics, independence and NOCLAR</td>
                            <td>February</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Annual IFRS for SME update</td>
                            <td>May</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Reporting engagements: Accounting officer, review and compilations</td>
                            <td>July</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Trusts and Deceased Estates</td>
                            <td>September</td>
                            <td>Trusts and Deceased Estates	September	4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>SARS and CIPC Compliance</td>
                            <td>October</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Year-end Tax Update</td>
                            <td>November</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        </tbody>
                    </table>
                ',
            ]);

            // Create new Plan
            $professional_accountant_plan = Plan::create([
                'name' => 'Professional Accountant',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '445',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            // Create new Plan
            $professional_accountant_plan_yearly = Plan::create([
                'name' => 'Professional Accountant',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '4806',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $professional_accountant->plans()->save($essentials);
            $professional_accountant->plans()->save($essentials_yearly);
            $professional_accountant->plans()->save($professional_accountant_plan);
            $professional_accountant->plans()->save($professional_accountant_plan_yearly);

            $business_accountant_in_practice = Profession::create([
                'title' => 'Business Accountant in Practice',
                'slug' => 'business-accountant-in-practice',
                'is_active' => true,
                'description' => '
                    <p>
                        The ESSENTIAL PLUS programme for Business Accountant in Practice includes the following events. Events can be attended either via seminar or webinar. Limited space at seminars so reserve your seat early. Webinars are recorded in studio and can be watched live or as a recording. Recordings are loaded in you online profile:
                    </p>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td>Event</td>
                                <td>Month</td>
                                <td>CPD points</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Monthly Compliance and Legislation Update </td>
                                <td>Every Month</td>
                                <td>24</td>
                            </tr>
                            <tr>
                                <td>Ethics, independence and NOCLAR</td>
                                <td>February</td>
                                <td>4 + 1 (One additional point for free assessment)</td>
                            </tr>
                            <tr>
                                <td>Companies Act and directors duties</td>
                                <td>March</td>
                                <td>4 + 1 (One additional point for free assessment)</td>
                            </tr>
                            <tr>
                                <td>Annual IFRS for SME update</td>
                                <td>May</td>
                                <td>Annual IFRS for SME update	May	4 + 1 (One additional point for free assessment)</td>
                            </tr>
                            <tr>
                                <td>Tax and IFRS for SME Reconciliations</td>
                                <td>June</td>
                                <td>4 + 1 (One additional point for free assessment)</td>
                            </tr>
                            <tr>
                                <td>Reporting engagements: Accounting officer, review and compilations</td>
                                <td>July</td>
                                <td>4 + 1 (One additional point for free assessment)</td>
                            </tr>
                            <tr>
                                <td>SARS and CIPC Compliance </td>
                                <td>October</td>
                                <td>SARS and CIPC Compliance 	October	4 + 1 (One additional point for free assessment)</td>
                            </tr>
                            <tr>
                                <td>Year-end Tax Update</td>
                                <td>November</td>
                                <td>4 + 1 (One additional point for free assessment)</td>
                            </tr>
                        </tbody>
                    </table>
                ',
            ]);

            // Create new Plan
            $business_accountant_in_practice_plan = Plan::create([
                'name' => 'Business Accountant in Practice',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '375',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            // Create new Plan
            $business_accountant_in_practice_plan_yearly = Plan::create([
                'name' => 'Business Accountant in Practice',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '4050',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $business_accountant_in_practice->plans()->save($essentials);
            $business_accountant_in_practice->plans()->save($essentials_yearly);
            $business_accountant_in_practice->plans()->save($business_accountant_in_practice_plan);
            $business_accountant_in_practice->plans()->save($business_accountant_in_practice_plan_yearly);

            $certified_bookkeeper = Profession::create([
                'title' => 'Certified Bookkeeper',
                'slug' => 'certified-bookkeeper',
                'is_active' => true,
                'description' => '
                    <p>
                        The ESSENTIAL PLUS programme for Certified Bookkeeper includes the following events. Events can be attended either via seminar or webinar. Limited space at seminars so reserve your seat early. Webinars are recorded in studio and can be watched live or as a recording. Recordings are loaded in you online profile:
                    </p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td>Event</td>
                            <td>Month</td>
                            <td>CPD points</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Monthly Compliance and Legislation Update </td>
                            <td>Every Month</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>Bookkeeping tips and tricks </td>
                            <td>February</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Using sofware tools for workflow management</td>
                            <td>March</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Preparing Management Accounts</td>
                            <td>May</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Drafting Budgets and cash-flow for business</td>
                            <td>June</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Working capital management</td>
                            <td>September</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>SARS issues and Tax Administration</td>
                            <td>October</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Annual Paye and VAT Update</td>
                            <td>November</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        </tbody>
                    </table>
                ',
            ]);

            // Create new Plan
            $certified_bookkeeper_plan = Plan::create([
                'name' => 'Certified Bookkeeper',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '300',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            // Create new Plan
            $certified_bookkeeper_plan_yearly = Plan::create([
                'name' => 'Certified Bookkeeper',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '3240',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $certified_bookkeeper->plans()->save($essentials);
            $certified_bookkeeper->plans()->save($essentials_yearly);
            $certified_bookkeeper->plans()->save($certified_bookkeeper_plan);
            $certified_bookkeeper->plans()->save($certified_bookkeeper_plan_yearly);

            $company_secretary = Profession::create([
                'title' => 'Company Secretary',
                'slug' => 'company-secretary',
                'is_active' => true,
                'description' => '
                    <p>
                        The ESSENTIAL PLUS programme for Company Secretary includes the following events. Events can be attended either via seminar or webinar. Limited space at seminars so reserve your seat early. Webinars are recorded in studio and can be watched live or as a recording. Recordings are loaded in you online profile:
                    </p>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td>Event</td>
                            <td>Month</td>
                            <td>CPD points</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Monthly Compliance and Legislation Update </td>
                            <td>Every Month</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>Annual legislation update affecting boards</td>
                            <td>TBC</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Governance: Accounting and tax issues for company secretaries</td>
                            <td>TBC</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Board management and Directors duties</td>
                            <td>TBC</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Company secretarial work</td>
                            <td>TBC</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Corporate governance and King 4</td>
                            <td>TBC</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Annual legislation update affecting boards</td>
                            <td>TBC</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Governance: Accounting and tax issues for company secretaries</td>
                            <td>TBC</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        </tbody>
                    </table>
                ',
            ]);

            // Create new Plan
            $company_secretary_plan = Plan::create([
                'name' => 'Company Secretary',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '350',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            // Create new Plan
            $company_secretary_plan_yearly = Plan::create([
                'name' => 'Company Secretary',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '3780',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $company_secretary->plans()->save($essentials);
            $company_secretary->plans()->save($essentials_yearly);
            $company_secretary->plans()->save($company_secretary_plan);
            $company_secretary->plans()->save($company_secretary_plan_yearly);

            $tax_practitioner = Profession::create([
                'title' => 'Tax Practitioner',
                'slug' => 'tax-practitioner',
                'is_active' => true,
                'description' => '
                    <p>
                        The ESSENTIAL PLUS programme for Tax Practitioner includes the following events. Events can be attended either via seminar or webinar. Limited space at seminars so reserve your seat early. Webinars are recorded in studio and can be watched live or as a recording. Recordings are loaded in you online profile:
                    </p>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td>Event</td>
                            <td>Month</td>
                            <td>CPD points</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Monthly Compliance and Legislation Update </td>
                            <td>Every Month</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>Interpreting financial statements </td>
                            <td>February</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>2018 Budget and Tax Update</td>
                            <td>March</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>VAT Risks and Issues in Business Operations (VAT201)</td>
                            <td>May</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Tax Planning</td>
                            <td>June</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Basics of IFRS for SME</td>
                            <td>July</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Trusts and Deceased Estates</td>
                            <td>September</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>Tax Admin and Disputes in Practice</td>
                            <td>October</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        <tr>
                            <td>2018 Year-End Tax Update</td>
                            <td>November</td>
                            <td>4 + 1 (One additional point for free assessment)</td>
                        </tr>
                        </tbody>
                    </table>
                ',
            ]);

            // Create new Plan
            $tax_practitioner_plan = Plan::create([
                'name' => 'Tax Practitioner',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '445',
                'interval' => 'month',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            // Create new Plan
            $tax_practitioner_plan_yearly = Plan::create([
                'name' => 'Tax Practitioner',
                'description' => 'Essential Plus<br> <small>Monthly Legislation Update PLUS additional Topics</small>',
                'price' => '4806',
                'interval' => 'year',
                'trial_days' => '0',
                'interval_count' => 1,
                'sort_order' => 1
            ]);

            $tax_practitioner->plans()->save($essentials);
            $tax_practitioner->plans()->save($essentials_yearly);
            $tax_practitioner->plans()->save($tax_practitioner_plan);
            $tax_practitioner->plans()->save($tax_practitioner_plan_yearly);
        });
    }
}
