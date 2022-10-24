<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mockery\Exception;

class cancelInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will cancel invoices provided';
    /**
     * @var CreditMemoRepository
     */
    private $creditMemoRepository;

    /**
     * Create a new command instance.
     * @param CreditMemoRepository $creditMemoRepository
     */
    public function __construct(CreditMemoRepository $creditMemoRepository)
    {
        parent::__construct();
        $this->creditMemoRepository = $creditMemoRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tobecancelled = [
            '034112',
            '034451',
            '034777',
            '037722',
            '039484',
            '039718',
            '039876',
            '039879',
            '025864',
            '025928',
            '025939',
            '025952',
            '025966',
            '025968',
            '026006',
            '026082',
            '026245',
            '026821',
            '027604',
            '028064',
            '028069',
            '028076',
            '028127',
            '028516',
            '028542',
            '028779',
            '028790',
            '028906',
            '028939',
            '029063',
            '029064',
            '029065',
            '029078',
            '029085',
            '029087',
            '029279',
            '029288',
            '029297',
            '029447',
            '029510',
            '030590',
            '030628',
            '030794',
            '030809',
            '030813',
            '030914',
            '030915',
            '030916',
            '030917',
            '030918',
            '030919',
            '030920',
            '030926',
            '030986',
            '031180',
            '031181',
            '031182',
            '031183',
            '031184',
            '031239',
            '031240',
            '031241',
            '031242',
            '031352',
            '031353',
            '031354',
            '031355',
            '031416',
            '031527',
            '031528',
            '031530',
            '031628',
            '032094',
            '032155',
            '032156',
            '033236',
            '033237',
            '033238',
            '033291',
            '033446',
            '033468',
            '033471',
            '033472',
            '033508',
            '033514',
            '033544',
            '033545',
            '033546',
            '033547',
            '033831',
            '034748',
            '035257',
            '035299',
            '035303',
            '035348',
            '035349',
            '035382',
            '035410',
            '035416',
            '035452',
            '035454',
            '035460',
            '035470',
            '035539',
            '035570',
            '035607',
            '035609',
            '035621',
            '035642',
            '035717',
            '035724',
            '035727',
            '035759',
            '035795',
            '035829',
            '035831',
            '035901',
            '035907',
            '035908',
            '035909',
            '036058',
            '036376',
            '036507',
            '036515',
            '036556',
            '036573',
            '036576',
            '036590',
            '036603',
            '036651',
            '036726',
            '036826',
            '036827',
            '036854',
            '036859',
            '036910',
            '036966',
            '037024',
            '037463',
            '037468',
            '037527',
            '037578',
            '037678',
            '037709',
            '037721',
            '037733',
            '037868',
            '037935',
            '037974',
            '038138',
            '038139',
            '038140',
            '038141',
            '038176',
            '038191',
            '038192',
            '038193',
            '038194',
            '038195',
            '038202',
            '038207',
            '038508',
            '038534',
            '038537',
            '038541',
            '038767',
            '038911',
            '039101',
            '039288',
            '039334',
            '039431',
            '039441',
            '039663',
            '039674',
            '040452',
            '040986',
            '041226',
            '041375',
            '041672',
            '041817',
            '042011'
        ];

        $this->info("We have ".count($tobecancelled).' Invoices that we are going to cancel');
        foreach ($tobecancelled as $cancelled){
            try{
                $invoice = Invoice::with('transactions')->where('reference', $cancelled)->get()->first();

                if ($invoice->status != 'paid' && $invoice->status != 'cancelled'){
                    if (! $invoice->note || $invoice->transactions->contains('Payment')){

                        $this->info("We are working with Invoice #".$cancelled);
                        $transaction = $invoice->transactions()->create([
                            'user_id' => $invoice->user->id,
                            'invoice_id' => $invoice->id,
                            'type' => 'credit',
                            'display_type' => 'Credit Note',
                            'status' => 'Closed',
                            'category' => $invoice->type,
                            'amount' => $invoice->balance,
                            'ref' => $invoice->reference,
                            'method' => 'Void',
                            'description' => "Invoice #{$invoice->reference} cancellation",
                            'tags' => "Cancellation",
                            'date' => Carbon::now()
                        ]);

                        $invoice->cancelled = 1;
                        $invoice->status = 'cancelled';
                        $invoice->save();

                        $this->creditMemoRepository->store($transaction);
                        $this->info("Creating cancellation note for profile");

                        $note = new Note([
                            'type' => 'general',
                            'description' => "Invoice #".$invoice->reference.' was cancelled due to non payment, No service supplied',
                            'logged_by' => "System",
                            'created_at' => Carbon::now()
                        ]);

                        if($invoice->ticket){
                            $invoice->ticket->delete();
                        }elseif ($invoice->order){
                            $invoice->order->delete();
                        }

                        $invoice->user->notes()->save($note);
                        $this->info("Successfully cancelled invoice ".$invoice->reference);
                    }
                }else{
                    $this->info('Invoice '.$invoice->reference.' cannot be cancelled due to invoice that has been paid/cancelled.');
                }
            }catch (Exception $exception){
                return;
            }
        }
    }
}
