<?php

namespace App\Http\Controllers\Admin;

use App\Body;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfessionalBodyDesignationController extends Controller
{
    public function store(Request $request)
    {
        $institute = Body::find($request['body_id']);
        $desgination = new Designation($request->except('_token'));
        $desgination->body()->associate($institute);
        $desgination->save();

        alert()->success('Your designation has been saved', 'Success!');
        return back();
    }

    public function update(Request $request, $id)
    {
        Designation::find($id)->update($request->all());
        alert()->success('Designation has been updated', 'Success!');
        return back();
    }

    public function destroy($id)
    {
        Designation::find($id)->delete();
        alert()->success('Designation has been deleted', 'Success!');
        return back();
    }
}
