<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Ticket;
use App\AppEvents\Venue;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = Ticket::findorFail($id);
        $event = Event::with('venues')->findorFail($ticket->event_id);
        $venues = $event->venues->pluck('name', 'id');
        $pricings = Pricing::where('event_id', $ticket->event_id)->get();
        $list = $pricings->pluck('form_help', 'id');

        return view('admin.members.pages.edit_ticket', compact('event', 'ticket', 'list', 'venues'));
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
        $input = $request->except(['_token']);

        // Find the ticket.
        $ticket = Ticket::find($id);

        // Find the venue.
        $venue = Venue::find($request->venue_id);

        // Update the ticket date to reflect the new venue date.
        $ticket->dates()->sync([$venue->dates->first()->id]);

        // Change the ticket name.
        $ticket->name = $venue->pricings->first()->name;

        // Change the ticket description.
        $ticket->description = $venue->pricings->first()->description;

        // Update ticket pricing id.
        $ticket->pricing_id = $venue->pricings()->first()->id;
        $ticket->save();

        // Save the ticket.
        $ticket->update($input);
        $ticket->save();

        alert()->success('Ticket has been updated', 'Success!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function destroy($id)
    {
        $ticket = Ticket::findorFail($id);
        $ticket->delete();
        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tickets' => 'required'
        ]);

        if ($validator->fails()){
            alert()->error('You need to select some tickets', 'Error');
            return back();
        }else{
            $tickets = $request->tickets;
            try{
                foreach ($tickets as $ticketId){
                    $ticket = Ticket::findorFail($ticketId);
                    $ticket->delete();
                }
            }catch (\Exception $exception){
                return $exception;
            }

            alert()->success('Tickets has been deleted!', 'Success!');
            return redirect()->back();
        }
    }

    public function MarkAsAttended()
    {
        $events = Event::all()->pluck('name', 'id');
        return view('admin.cpd.webinar_assign', compact('events'));
    }

    public function PostMarkAsAttended(Request $request)
    {
        $event = Event::findorfail($request->event);

        if ($request->hasFile('file')){
            $file = Excel::load($request->file('file'), function($reader) {})->get();

            if(count($file) > 0) {
                $failed = 0;
                $success = 0;
                $invalidUsers = collect();

                foreach ($file as $staffMember) {
                    $data = $staffMember->toArray();

                    if(empty($data['email'])) {
                        $failed++;
                        continue;
                    }

                    $user = User::where('email', $data['email'])->first();
                    if ($user){

                        $ticket = $user->tickets->where('event_id', $event->id)->filter(function ($tick){
                            if ($tick->venue->type == 'online'){
                                return $tick;
                            }})->first();

                        if ($ticket){
                            if ($request->attendance == 'attended'){
                                $ticket->update(['attended' => true]);
                                $ticket->save();
                            }else{
                                $ticket->update(['attended' => false]);
                                $ticket->save();
                            }
                           $success++;
                        }else{
                            $failed++;
                            continue;
                        }
                    }else{
                        $invalidUsers->push($data['email']);
                        $failed++;
                        continue;
                    }
                }

                if($success == 0) {
                    alert()->error("Something went wrong, we were not able to read any of the members that you have listed, please insure that your file is .XLS and that you have an 'email' column", "Whoops!")->persistent('Close');
                    return redirect()->back();
                } else {
                    alert()->success("Your members has been processed, {$success} members was validated successfully and {$failed} could not be verified, an email with data has been sent to ".config('app.email'), 'Success!')->persistent('Close');
                    $this->doExport($invalidUsers);
                    return redirect()->back();
                }
            } else {
                alert()->error('No members found in uploaded file, please try again', 'Error!')->persistent('Close');
                return redirect()->back();
            }
        }
    }

    public function doExport($data)
    {
        Excel::create('invalid_users', function ($excel) use ($data) {
            $excel->sheet('Invalid Users', function ($sheet) use ($data) {
               foreach ($data as $email){
                   $sheet->appendRow([$email]);
               }
            });
        })->store('xls', storage_path('app/public/exports'));

        $location = storage_path('app/public/exports/invalid_users.xls');

        \Mail::send('emails.attendees.invalid', ['location' => $location] , function ($m) use ($location) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'))->subject('Unable to find webinar attendees imported');
            $m->attach($location);
        });
    }
}
