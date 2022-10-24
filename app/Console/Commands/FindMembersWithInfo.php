<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class FindMembersWithInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will return a list of members for you. ';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $emails = [
            'david@afts.co.za',
            'info@dhacc.co.za',
            'jenvwa@gmail.con',
            'magriet@profin.co.za',
            'mashudu@everydayap.co.za',
            'natasha@leelex.co.za',
            'rgulle@rgulle.com',
            'znqitsi@gmail.com',
            'anja@hhaccounting.co.za',
            'fpvandyk@outlook.com',
            'tax@sharondotjones.com',
            'ken@kbfbusiness.com',
            'bsi@vodamail.co.za',
            'egchap@global.co.za',
            'iaa@vox.co.za',
            'jcbsctz@yahoo.co.uk',
            'mmeyer@denovobus.co.za',
            'nicels@midbank.co.za',
            'peter@graysadmin.co.za',
            'reinard@vvsacc.co.za',
            'accountax@live.co.za',
            'evelyn@certaca.co.za',
            'yakesh.parag@grinaker-lta.co.za',
            'katherine@graysadmin.co.za',
            'bmuller@bmfs.web.za',
            'drotsky@iafrica.com',
            'henriettes@lantic.net',
            'joel.nick@gmail.com',
            'michelle@atomic-ac.co.za',
            'megan.passemiers@gmail.com',
            'pdw@amtsolutions.co.za',
            'riaan@discoverymail.co.za',
            'accounts@avstaging.co.za',
            'gary@gmeaccounting.co.za',
            'swanepoel.chantelle@yahoo.co.za',
            'ksnsolutions@gmail.com',
            'dcv@vodamail.co.za',
            'eddie@clabo.co.za',
            'jacqueline@kumula.co.za',
            'kimflack101@gmail.com',
            'magalela76@gmail.com',
            'mary-jane@finleys.co.za',
            'paule@erasmusconsulting.co.za',
            'seligs@yebo.co.za',
            'accountantjhb1@ipacc.co.za',
            'fin@ethicaaccounting.co.za',
            'theo.sibango@gmail.com',
            'laurikefourie@gmail.com',
            'charles@fortiss.co.za',
            'dustinr@sivest.co.za',
            'jagga@dtaudit.co.za',
            'janinebuntin@gmail.com',
            'mahmood@auditconnection.co.za',
            'mandy@gmgfinancial.com',
            'robert@rawdata.co.za',
            'anisaaloonat@vodamail.co.za',
            'gildas@mahouatax.co.za',
            'shaun@bespokeaccsol.co.za',
            'louisefabersa@gmail.com',
            'chinique@noble-acc.co.za',
            'danpoope@lantic.net',
            'info@cliffaccountants.co.za',
            'jennyv@finovest.co.za',
            'mccanvas@mccanvas.co.za',
            'mskotton@gmail.com',
            'peter@iridium.co.za',
            'andrewe.jsa@gmail.com',
            'felicia.august@gmail.com',
            'smdutoit@mweb.co.za',
            'lynnesmit@mweb.co.za',
            'cas_services@outlook.com',
            'duwayne.kock@gmail.com',
            'iqtaxandacc@gmail.com',
            'judy@a2zaccounting.co.za',
            'mramalata@gmail.com',
            'marnusvheerden@gmail.com',
            'slabbertb@yahoo.com',
            'bsteveni@marshalls.co.za',
            'efiandrew@yahoo.com',
            'zdzunisani@gmail.com',
            'lbetwel@gmail.com',
            'cradocksaad2@isat.co.za',
            'dirk@dcoetzee.co.za',
            'iziennegrib@gmail.com',
            'jolandie@noble-acc.co.za',
            'mbester7072@gmail.com',
            'nelmi@uysfin.co.za',
            'patrick.phakathi@ekurhuleni.gov.za',
            'acoxrekenmeester@gmail.com',
            'gkekana@webmail.co.za',
            'surie@kalyan.co.za',
            'karin@bvkfin.co.za',
            'charles.rembe@chagga.co.za',
            'elsabe@bstax.co.za',
            'hayley@lutrin.co.za',
            'johan@tutin.co.za',
            'molopo@lantic.net',
            'miansm4591@gmail.com',
            'roelf.brink@simplyaccounting.co.za',
            'andre@matimbers.co.za',
            'franschhoek@taxshop.co.za',
            'sanchia.veale@gmail.com',
            'krugerw@polka.co.za',
            'brigitte.fh@telkomsa.net',
            'dolishrag@mweb.co.za',
            'hs.landsberg@absamail.co.za',
            'martin@watson-inc.co.za',
            'mike@burgerandbuurman.co.za',
            'ryan@summitaccounting.co.za',
            'amy.dacruz@ontec.co.za',
            'elsa@burgerandbuurman.co.za',
            'schalk@vantagepointca.co.za',
            'louise@snymanrek.co.za',
            'dfa@netactive.co.za',
            'henann1@telkomsa.net',
            'martinduplessis@mymtnmail.co.za',
            'motholi@yahoo.com',
            'rose@bbpractice.co.za',
            'admin@lubbelubbe.co.za',
            'fanie@rta.co.za',
            'veronica@minven.co.za',
            'kobusgrobler@hotmail.com',
            'blueorange246@gmail.com',
            'info@osithele.co.za',
            'maphokoh@gmail.com',
            'rumentha@rnraccounting.co.za',
            'admin@financial-mill.co.za',
            'welma@burgerandbuurman.co.za',
            'lisabiggs12@gmail.com',
            'bianne@hickmanandhickman.co.za',
            'iryklief75@gmail.com',
            'martin@bvkfin.co.za',
            'petunias71@gmail.com',
            'aemoller28@gmail.com',
            'simon@leagroup.co.za',
            'lisafranklinca@gmail.com',
            'charne@topazaccounting.com',
            'mrbotha@mweb.co.za',
            'bilal@auditconnection.co.za',
            'willem@assuredbookkeeping.co.za',
            'carel@edensupport.co.za',
            'andre@kretch.co.za',
            'theresa@n-e-fg.com',
            'darryll@tax911.co.za',
            'berry.c@mweb.co.za',
            'zbnts@yahoo.com',
            'chrisyoung@taproottechnologies.com',
            'accounts@myatazatps.co.za',
            'suhayl@auditconnection.co.za',
            'charles@cfaccounting.co.uk',
            'alice@alicebc.co.za',
            'warren@dcoetzee.co.za',
            'chrizaan@topigssa.co.za',
            'andries@mtabhm.co.za',
            'simon@iridium.co.za',
            'carel@lantic.net',
            'anne@taccs.co.za',
            'yvettekok@vodamail.co.za',
            'cmvisagie@telkomsa.net',
            'andery@telkomsa.net',
            'thomas@tvsca.com',
            'chez@netactive.co.za',
            'andre.vanwyk@vanwykca.co.za',
            'shanita@sewpath.co.za',
            'chrisvr@telehost.co.za',
            'annekevanwyk@live.com',
            'stopower4@yahoo.com',
            'brett@bmaaudit.co.za',
            'annemarie.m.nel@gmail.com',
            'valda.steyn@arrowscon.co.za',
            'bwe.a3s@gmail.com',
            'trevor@watson-inc.co.za',
            'bigmo@iburst.co.za',
            'younus@amods.co.za',
            'cliffgulston@gmail.com',
            'vernong@live.co.za',
            'christo.wess@gmail.com',
            'welmapaul@lantic.net',
            'butana1@live.co.za',
            'carollugulwana@pnp.co.za',
            'darien@asper.za.net',
            'deleebeye@yahoo.com',
            'bma@global.co.za',
            'chantellew4@gmail.com',
            'claudetteuys@gmail.com',
            'baiter.josef@gmail.com'
        ];

        $users = collect();
        foreach ($emails as $email){
            $dataUser = User::where('email', $email)->first();
            if ($dataUser){
                $users->push($dataUser);
            }
        }

        Excel::create('Users Report', function($excel) use($users) {
            $excel->sheet('Users', function($sheet) use($users) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'cell',
                    'Package',
                    'Status',
                    'Owner',
                ]);

                foreach ($users as $user) {
                    $this->info('Adding '.$user->first_name);
                    $sheet->appendRow([
                        $user->first_name,
                        $user->last_name,
                        $user->email,
                        $user->cell,
                        ($user->subscription('cpd')? $user->subscription('cpd')->plan->name: "-"),
                        $user->status,
                        ($user->subscription('cpd') ? $user->subscription('cpd')->SalesAgent()->first_name.' '.$user->subscription('cpd')->SalesAgent()->last_name : "-"),
                    ]);
                }

            });
        })->store('xls', storage_path('exports'));
    }
}
