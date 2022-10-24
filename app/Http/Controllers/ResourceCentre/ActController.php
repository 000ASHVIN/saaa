<?php

namespace App\Http\Controllers\ResourceCentre;

use App\Act;
use App\ActList;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActController extends Controller
{

    public function show($act_slug)
    {
        $actList = ActList::where('slug', "{$act_slug}/")->firstOrFail();

        $acts = Act::where('act_id', $actList->id)
                    ->where('is_toc_item', 1)
                    ->whereNull('parent_id')
                    ->with('children')
                    ->get();

        return view('resource_centre.acts.show', compact('acts', 'actList'));
    }

    public function showAct($actId)
    {
        $act = Act::find($actId);
        return view('resource_centre.acts.single', compact('act'));
    }
}
