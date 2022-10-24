<?php

namespace App\Http\Controllers\Admin;

use App\Assessments\Attempt;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminAssessmentAttemptController extends Controller
{
    public function update(Request $request, $id)
    {
        $attempt = Attempt::find($id);
        $attempt->update($request->only('passed'));

        alert()->success('Attempt has been saved!', 'Success!');
        return back()->withInput(['tab' => 'member_assessments']);
    }

    public function destroy($id)
    {
        $attempt = Attempt::find($id);
        $attempt->delete();

        alert()->success('Attempt has been removed!', 'Success!');
        return back()->withInput(['tab' => 'member_assessments']);
    }
}
