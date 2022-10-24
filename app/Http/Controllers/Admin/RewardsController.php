<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\SponsorFormSubmission;
use App\Http\Controllers\Controller;
use App\Repositories\DatatableRepository\DatatableRepository;
use Maatwebsite\Excel\Facades\Excel;

class RewardsController extends Controller
{
    private $datatableRepository;

    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
          return view('admin.reward.index');
    }

    
    public function list_reward()
    {
        return $this->datatableRepository->list_reward();
    }

    public function reward_export_export(Request $request)
    {
        $SponsorFormSubmission = SponsorFormSubmission::all();

        Excel::create('Rewards Report', function ($excel) use ($SponsorFormSubmission) {
            $excel->sheet('Sponsor Data', function ($sheet) use ($SponsorFormSubmission) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Contact Number',
                    'Product',
                    'Age',
                    'Accountant Type',
                    'Gender',
                    'Race',
                    'Level of Management',
                    'Income',
                    'Registered Professional Accountancy Body',
                    'Professional Body Name',
                    'Other Professional Body Name',
                    'Do You Adhere To A Code Of Conduct',
                    'Are your cpd hours up to date',
                    'Do you use engagement letters',
                    'Latest technical knowledge or library',
                    'Do you have the required infrastructure',
                    'Do you or your firm perform reviews of all work',
                    'Do you apply relevant auditing and assurance standards',
                    'Do you use the latest technology and software'
                ]);

                foreach ($SponsorFormSubmission as $sponsor) {
                    $sheet->appendRow([
                        $sponsor->name,
                        $sponsor->email,
                        $sponsor->contact_number,
                        $sponsor->product,
                        $sponsor->age,
                        $sponsor->accountant_type,
                        $sponsor->gender,
                        $sponsor->race,
                        $sponsor->level_of_management,
                        $sponsor->income,
                        ($sponsor->registered_professional_accountancy_body==0)?'No':'Yes',
                        $sponsor->professional_body_name,
                        $sponsor->other_professional_body_name,
                        ($sponsor->do_you_adhere_to_a_code_of_conduct==0)?'No':'Yes',
                        ($sponsor->are_your_cpd_hours_up_to_date==0)?'No':'Yes',
                        ($sponsor->do_you_use_engagement_letters==0)?'No':'Yes',
                        ($sponsor->latest_technical_knowledge_or_library==0)?'No':'Yes',
                        ($sponsor->do_you_have_the_required_infrastructure==0)?'No':'Yes',
                        ($sponsor->do_you_or_your_firm_perform_reviews_of_all_work==0)?'No':'Yes',
                        ($sponsor->do_you_apply_relevant_auditing_and_assurance_standards==0)?'No':'Yes',
                        ($sponsor->do_you_use_the_latest_technology_and_software==0)?'No':'Yes'
                    ]);
                }

            });
        })->export('xls');

        return back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reward = SponsorFormSubmission::find($id);
        $product = [];
        if($reward)
        {
            $product[$reward->product] = $reward->product;
        }
        return view('admin.reward.show', compact('reward','product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $rewards = SponsorFormSubmission::find($id);
        $rewards->update($request->except('_token'));

        alert()->success('Your rewards Data has been udpated successfully', 'Success!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
