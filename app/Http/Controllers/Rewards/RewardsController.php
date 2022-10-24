<?php

namespace App\Http\Controllers\Rewards;

use App\DraftWorx;
use App\Jobs\SendDraftWorxAutomatedMailerToClient;
use App\Repositories\SmsRepository\SmsRepository;
use App\SponsorFormSubmission;
use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\SponsorList;

class RewardsController extends Controller
{
    private $smsRepository;

    public function __construct(SmsRepository $smsRepository)
    {
        $this->smsRepository = $smsRepository;
    }

    public function index()
    {
        $sponsor = SponsorList::where('is_active','1')->get()->pluck('name')->toArray();
        $sponsor = array_map('strtolower', $sponsor);
        // $sponsorList = SponsorList::where('is_active','1')->get();
        $sponsorList = SponsorList::where('is_active','1')->orderBy('created_at', 'DESC')->get(); 

        return view('pages.rewards.index',compact('sponsor','sponsorList'));
    }

    public function show($slug)
    {
        $content = SponsorList::where('slug',$slug)->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
            return redirect()->back();
        }
        $product[$slug]=$content->name;
        return view('pages.rewards.show',compact('content','product'));
    }

    public function draftworx()
    {
        $content = SponsorList::where('name','draftworx')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
            return redirect()->back();
        }
        $product['draftworx']='draftworx';
        $slug ='draftworx';
        $title = '';
        $logo ='/assets/frontend/images/sponsors/draftworx.jpg';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function taxshop()
    {
        $content = SponsorList::where('name','taxshop')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
            return redirect()->back();
        }
        $product['taxshop']='taxshop';
        $slug ='taxshop';
        $title = '';
        $logo ='/assets/frontend/images/sponsors/tax-shop.png';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function draftworx_store(Request $request)
    {
        $this->validate($request, [
            'quote' => 'required',
            'email' => 'required',
            'company_trading_name' => 'required',
            'physical_business_address' => 'required',
            'first_name' => 'required',
            'surname' => 'required',
            'contact_number' => 'required',
            'id_or_passport' => 'required',
            'type_of_subscription' => 'required',
            'professional_body' => 'required',
            'number_of_licenses' => 'required',
            'applies_to_you' => 'required',
            'type_of_business' => 'required',
        ]);

        $data = DraftWorx::create($request->except('_token'));

        // Send Mailer to client.
        if ($request->quote == true || $request->quote == '1') {
            $job = (new SendDraftWorxAutomatedMailerToClient($data));
            $this->dispatch($job);
        }

        // Send SMS to Ronell.
        list($member, $data) = $this->FindRonell($request);
        // $this->sendSms($data, $member);

        alert()->success('DRAFTWORX will contact you to explain how you can claim your discount.', 'Success!');
        return back();
    }

    public function draftworx_draftworx_applications()
    {
        $data = DraftWorx::all();
        Excel::create('Draftworx Applications', function ($excel) use ($data) {
            $excel->sheet('Applications', function ($sheet) use ($data) {
                $sheet->appendRow(
                    [
                        'Email Address',
                        'Company Trading Name',
                        'Physical address of Business',
                        'VAT number (if registered)',
                        'Contact person - first name',
                        'Contact person - surname',
                        'Contact Number',
                        'ID or Passport number',
                        'Type of SAAA subscription',
                        'Member of which professional body?',
                        'Number of user licences required',
                        'Which applies to you?',
                        'Your type of business',
                        'Quote'
                    ]
                );
                foreach ($data as $data) {
                    $sheet->appendRow([
                        $data->email,
                        $data->company_trading_name,
                        $data->physical_business_address,
                        ($data->vat_number ?: '-'),
                        ucfirst($data->first_name),
                        ucfirst($data->surname),
                        $data->contact_number,
                        $data->id_or_passport,
                        $data->type_of_subscription,
                        $data->professional_body,
                        $data->number_of_licenses,
                        $data->applies_to_you,
                        $data->type_of_business,
                        ($data->quote ? 'Yes' : 'No'),
                    ]);
                }
            });
        })->export('xls');
    }

    public function bluestar()
    {
        $content = SponsorList::where('name','bluestar')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
            return redirect()->back();
        }
        $product['aon']='AON';
        $product['sanlam']='Sanlam';
        $product['santam']='Santam';
        $slug ='saiba';
        $title = '';
        $logo ='/assets/frontend/images/sponsors/bluestar.jpg';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function acts()
    {
        $content = SponsorList::where('name','acts')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
        }
        $product['acts']='ACTS Online';
        $slug ='saiba';
        $logo ='/assets/frontend/images/sponsors/acts.jpg';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function quickbooks()
    {
        $content = SponsorList::where('name','quickbooks')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
        }
        $product['quickbooks']='QuickBooks';
        $slug ='saiba';
        $logo ='';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function saiba()
    {
        $content = SponsorList::where('name','saiba')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
        }
        $product['SAIBA']='SAIBA';
        $slug ='saiba';
        $logo ='/assets/frontend/images/sponsors/saiba.jpg';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function EdNVest()
    {
        $content = SponsorList::where('name','EdNVest')->where('is_active','1')->first();
        if(!$content)
        {
            alert()->error('You do not have permission', 'Sorry');
        }
        $product['EdNVest']='EdNVest';
        $slug ='EdNVest';
        $logo ='/assets/frontend/images/sponsors/EdNVest logo.png';
        
        // return view('pages.rewards.reward',compact('content','product','logo','slug'));
        return view('pages.rewards.show',compact('content','product','logo','slug'));
    }

    public function InfoDocs()
    {
        $content = SponsorList::where('name','InfoDocs')->where('is_active','1')->first();
        if(!$content)
        { 
            alert()->error('You do not have permission', 'Sorry');
        }
        $product['InfoDocs']='InfoDocs';
        $slug ='InfoDocs';
        $logo ='/assets/frontend/images/sponsors/infodocs-partner-logo-square.png';
        
        return view('pages.rewards.show',compact('content','product','logo','slug'));
        // return view('pages.rewards.InfoDocs',compact('content','product','logo','slug'));
    }


    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'contact_number' => 'required|numeric',
        //     'product' => 'required',
        //     'professional_body_name' => 'required',
        //     'other_professional_body_name' => 'required_if:professional_body_name,OTHER'
        // ]);

        $submission = SponsorFormSubmission::create($request->except('_token'));

        $toEmail = [];
        $content = SponsorList::where('slug',$request['product'])->first();

        $question = config('sponsor_questions');
       
        $questions = explode(",",$content->question_id);
        $selected = [];
        foreach($question as $no=>$q){
            if(in_array($no,$questions)){
                $selected[]= $q;
            }
        }
        
        if($content){
            if($content->email_id != ""){
              $toEmail = explode(",",$content->email_id);  
            }
        }
        if ($request['product'] == 'Draftworx' || $request['product'] == 'SAIBA') {
            list($member, $data) = $this->FindRonell($request);

            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission,'selected'=>$selected], function ($m) use ($toEmail) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($toEmail)->subject('New Sponsor Page Form Submission');
            });

            // $this->sendSms($data, $member);
        } elseif ($request['product'] == 'Webinars') {
            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission,'selected'=>$selected], function ($m)  use ($toEmail)  {
                $m->from(config('app.email'), config('app.name'));
                $m->to(config('app.email'))->subject('New Sponsor Page Form Submission');
            });
        }elseif ($request['product'] == 'EdNVest' || $request['product'] == 'InfoDocs') {
            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission,'selected'=>$selected], function ($m)  use ($toEmail)  {
                $m->from(config('app.email'), config('app.name'));
                $m->to(config('app.email'))->subject('New Sponsor Page Form Submission');
            });
        } elseif ($request['product'] == 'quickbooks') {
            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission,'selected'=>$selected], function ($m)  use ($toEmail)  {
                $m->from(config('app.email'), config('app.name'));
                $m->to($toEmail)->subject('New Rewards Page Form Submission');
            });
        } elseif (!empty($toEmail)){
            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission,'selected'=>$selected], function ($m)  use ($toEmail)  {
                $m->from(config('app.email'), config('app.name'));
                $m->to($toEmail)->subject('New Rewards Page Form Submission');
            });
        } else {
            $member = User::find(7686);
            $data = collect([
                'Chantal' => [
                    'number' => '27799337749',
                    'message' => 'New ' . ucfirst($request['product']) . ' application received from ' . ucfirst($request['name'])
                ],
                'Stiaan' => [
                    'number' => '27827876539',
                    'message' => 'New ' . ucfirst($request['product']) . ' application received from ' . ucfirst($request['name'])
                ]
            ]);

            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission], function ($m)  use ($toEmail)  {
                $m->from(config('app.email'), config('app.name'));
                $m->to($toEmail)->subject('New Sponsor Page Form Submission');
            });

            // $this->sendSms($data, $member);
        }

        alert()->success('Thank you for completing the form', 'Success!');
        return back();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function FindRonell(Request $request)
    {
        $member = User::find(18);
        $data = collect([
            'Ronel' => [
                'number' => '0027832867350',
                'message' => 'New ' . ucfirst($request['product']) . ' application received from ' . ucfirst(($request['name'] ? $request['name'] : $request['first_name']))
            ]
        ]);
        return [$member, $data];
    }

    /**
     * @param $data
     * @param $member
     */
    public function sendSms($data, $member)
    {
        foreach ($data as $notify) {
            $this->smsRepository->sendSms($notify, $member);
        }
    }
}
