<?php

namespace App\Console;

use App\DebugLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
//         \App\Console\Commands\UpdateInvoices::class,
        // Commands\ProcessBulkMails::class,
        // Commands\FixSubscriptions::class,
        // Commands\ProcessInstallments::class,
        // Commands\UpdateOverdueSubscriptions::class,
        // Commands\AutoAllocateSurplusInvoiceBalanceToNextInstallments::class,
        // Commands\BreakLargerPaymentsDownIntoSmallerOnes::class,
        // Commands\FixInstallmentsLineItemDiscount::class,
        // Commands\FixImportedInvoices::class,
//         Commands\AssignSubscriptionEventsToSubscribers::class,
         Commands\SendOverdueInvoiceEmail::class,
         Commands\eventReminder::class,
         Commands\YearlyPracticePlan::class,
//        Commands\SwitchSubscriptions::class,
//        Commands\GenerateDecemberInvoicesForOldSubscriptions::class,
//        Commands\CoppyIdNumberAndCellphone::class,
        Commands\RenewSubscriptions::class,
        Commands\send_ptp_arrangements_to_accounts_daily::class,
        Commands\SuspendIfUnpaidEft::class,
        Commands\UpdateComprehensiveFeatures::class,
//        Commands\assignSaitSubscriptions::class,
        Commands\UpdateSubscriptionPaymentMethod::class,
        Commands\cleanSubscriptions::class,
//        Commands\UpgradeOldSubscribersToNewPackage::class,
        Commands\RemoveDuplicateTickets::class,
        Commands\ExtractDebtors::class,
//        Commands\SendDebtEmailToOverdueInvoices::class,
//        Commands\UpdateUserDebitOrderDetails::class,
//        Commands\FixEventTickets::class,
//        Commands\MigrateAllPaymentMethodsToUser::class,
//        Commands\CancelOverdueSubscriptions::class,
//        Commands\backdateUserNotes::class,
        Commands\SendSuspendedNotifications::class,
        Commands\cancelInvoices::class,
        Commands\SendAccountStatement::class,
        Commands\KeepCPDSubsMailingUpToDate::class,
        Commands\assignEventsToFreeMembers::class,
        Commands\AssignNewTopicsToOldPlans::class,
        Commands\AllocatePracticePlanToUser::class,

        Commands\MarkInvoicesAsClaimed::class,
//        Commands\AddNoteToUsersProfile::class,
//        Commands\UpdatePresenterPositions::class,
        Commands\sendInvoiceToAllOpenAndUnpaidInvoices::class,
        Commands\AnnaulRenewalNotification::class,

//        Commands\Create2018Plans::class,
//        Commands\SuspendAccounts::class,
//        Commands\CreditMemos::class,

        /*
         * Send Suspension Report
         */
        Commands\SendSystemReport::class,
        Commands\assignWebinarVideosToClients::class,
        Commands\AssingVideoPerEvent::class,

        Commands\SuspendClientsAtMidnight::class,
        Commands\ProcessPerPeriodReport::class,
        Commands\ProcessPerPeriodOrdersReport::class,
        Commands\ProcessUnpaidInvoicesReport::class,
        Commands\ProcessCourseInstallment::class,

        Commands\assignCpdsCategories::class,

        // Combo Mailer Queue
        Commands\QueueComboMailer::class,
        Commands\NotifyPromiseToPayClients::class,
        Commands\updateVideosTable::class,
        Commands\ProcessDebitOrders::class,
        Commands\copyAccountHolderNameAndIdNumbers::class,
        Commands\CheckForDuplicateVideos::class,
        Commands\cancelOverdueorders::class,
        Commands\AssignInvoiceIdToTickets::class,
        Commands\GenerateSubscriptionTickets::class,
        Commands\GenerateFreeSubscriptionTickets::class,
        Commands\GenerateVideoTickets::class,
        Commands\GenerateFreeVideoTickets::class,
        Commands\FindUsers::class,
        Commands\DropBoxInvoice::class,
        Commands\DropBoxCPDCertificates::class,

        /*
         * Database Cleanup!
         */
        Commands\ProcessExportReports::class,
        Commands\FixDupliacteEventVenues::class,
        Commands\FixDupliacteEventPresenters::class,
        Commands\FixDupliacteCpdEntries::class,
        Commands\FixDuplicateDateTickets::class,
        Commands\assignDayThreeForBootCampAttendees::class,
        Commands\RoundRobinFreeUsersToSalesGuys::class,
        Commands\GenerateActDescripton::class,
        Commands\PullActs::class,
        Commands\RemoveTickets::class,
        Commands\AddMissingWallets::class,
        Commands\CheckForNoFebInvoices::class,
        Commands\PullSolutions::class,

        Commands\NewEventNotification::class,
        Commands\EventBookingReminders::class,

        /*
        * Handesk sync
        */
        Commands\HandeskSynchCron::class,

        /*
        * Manual script
        */
        Commands\TTFWebinarSeriesManual::class,
        Commands\FaqTagMappingScript::class,
        Commands\HandeskFaqMigration::class,
        Commands\TTFManualHandeskAssignAgentGroups::class,

        Commands\ManualAddLeadActivities::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Assing Topics / Events to Subscriptions
         */
//        $schedule->command('assign:saitCPD')
//            ->hourly();

        $schedule->command('reports:per-period')
            ->everyThirtyMinutes();

        $schedule->command('reports:per-period-orders')
            ->everyThirtyMinutes();

        
        $schedule->command('process:export:reports')
            ->everyThirtyMinutes();    
    
        $schedule->command('report:unpaidInvoicesExtract')
            ->everyThirtyMinutes();

        $schedule->command('handesk:sync')
            ->everyThirtyMinutes();

        // Assign all CPD Susbcribers Events.
        $schedule->command('generate:subscriber-tickets')
            ->hourly();

        $schedule->command('generate:free-subscriber-tickets')
            ->hourly();
        /**
         * Assign Videos to Clients.
         */
        $schedule->command('assign:purchasedWebinar')
            ->dailyAt('21:00');

        // Check for new Free Members that does not have anything.
        $schedule->command('free:round-robin')
            ->cron('0 */3 * * *');

        //Process Course Installment on 1st of every Month    
        $schedule->command('process:course:installment')
            ->cron('0 1 1 * *');                

        /*
         * Check for system duplicates and fix.
         * Events
         * Event Presenters
         * Cpd Hours Logged
         * Ticket Dates
         */
        $schedule->command('fix:event-venues')
            ->dailyAt('22:00');

        $schedule->command('fix:event-presenters')
            ->dailyAt('22:15');

        $schedule->command('fix:cpds')
        ->dailyAt('22:30');

        $schedule->command('fix:ticket-dates')
            ->dailyAt('23:00');

        $schedule->command('upgrade:free')
            ->dailyAt('23:30');

        /**
         * Run Subscriptions Renewals
         */
        $schedule->command('subscriptions:renew')
            ->dailyAt('00:00');

        $schedule->command('generate:video-tickets')
            ->hourly();    
        $schedule->command('generate:free-video-tickets')
            ->hourly();     

        $schedule->command('process:debit-orders')
            ->dailyAt('01:00');

        $schedule->command('year:practice:plan')
            ->dailyAt('02:00');

        $schedule->command('suspend:midnight')
            ->dailyAt('01:30');

        $schedule->command('event:reminders')
            ->dailyAt('05:00');

        $schedule->command('event:new-event-notification')
            ->dailyAt('06:00');

        $schedule->command('notifications:annaul-renewal')
            ->dailyAt('06:30');

        $schedule->command('event:booking-reminders')
            ->weekdays()->dailyAt('06:00');

        $schedule->command('system:reports')
            ->dailyAt('03:00');

        $schedule->command('assign:events-free-members')
            ->dailyAt('03:00');

        $schedule->command('queue:combo')
            ->weekly()->tuesdays()->at('03:30');

        $schedule->command('overdue:invoices')
            ->weekly()->wednesdays()->at('04:00');

        $schedule->command('clean:subscriptions')
            ->dailyAt('08:00');

        $schedule->command('send:ptp')
            ->dailyAt('17:00');

        $schedule->command('suspend:eftunpaids')
            ->dailyAt('17:15');

        $schedule->command('MailingUpdate:CPDSubs')
            ->dailyAt('20:10');

        $schedule->command('wallets:create')
            ->dailyAt('17:30');

        $schedule->command('pull:solutions')
            ->dailyAt('18:00');
        
        $schedule->command('pull:acts')
            ->weekly()->fridays()->at('05:00');    

//        $schedule->command('send:notifications')
//            ->dailyAt('23:00');


        /**
         * Run Backups
         */
        $schedule->command('backup:run --only-db')
            ->before(function () {
                DebugLog::log('Starting backup.');
            })

            ->after(function () {
                DebugLog::log('Backup complete.');
            })->dailyAt('23:00');
    }
}
