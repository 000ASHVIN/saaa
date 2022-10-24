<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Invoice;
use App\Note;
use App\Notes;
use App\Rep;
use App\Users\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserNotesController extends Controller
{
    public function store(Request $request, $id)
    {
        $user = User::find($id);

        $note = new Note([
            'type' => $request->type,
            'description' => $request->description,
            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
        ]);

        $user->notes()->save($note);
        alert()->success('Your note has been added', 'Success!');
        return back()->withInput(['tab' => 'account_notes']);
    }

    public function update($id, Request $request)
    {
        $invoice = Invoice::where('reference', 'LIKE', str_replace('#', '', $request->invoice_reference))->get();
        $rep = Rep::find($request->logged_by)->user->name;
        $note = Note::find($id);

        $note->update(['logged_by' => $rep,]);
        $note->invoice()->associate($invoice->first());
        $note->save();

        alert()->success('Your note was updated successfully.', 'Success!');
        return back();
    }

    public function complete(Request $request, $id, $user)
    {
        $note = Note::find($id);
        $note->update(['completed' => true]);

        $user = User::find($user);
        $note = New Note([
            'type' => 'general',
            'description' => 'The Payment arrangement has been completed and we have received the funds.',
            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
        ]);
        $user->notes()->save($note);

        alert()->success('The payment arrangement has been completed!', 'Thank you!');
        return back()->withInput(['tab' => 'account_notes']);

    }

    public function destroy($id)
    {
        $note = Note::find($id);
        $note->delete();

        alert()->success('Your note has been deleted', 'Success!');
        return back()->withInput(['tab' => 'account_notes']);
    }
}
