<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\Http\Requests;

use App\AppEvents\Pricing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSettingsController extends Controller
{
    public function index(Request $request)
    {
        $config = Config::all();

        return view('admin.config.index', compact('config'));
    }

    public function store(Request $request)
    {
        try{
            $data = $request->all();
            $removeIds = $request->remove_ids;
            $removeIds = explode(",",$removeIds);
            $configIds = Config::whereIn('id',$removeIds)->delete();
            $ids = $request->id;
            $config = collect();
            foreach($data['options'] as $k=>$v){
                if($ids[$k]>0){
                    $config = Config::find($ids[$k]);    
                    $config->options = $v;
                    $config->value = $data['value'][$k];
                    $config->save();
                }else{
                    if($v!="" && $data['value'][$k]!=""){
                        $newConfig = New Config(['options'=>$v,'value'=>$data['value'][$k]]);
                        $newConfig->save();
                    }
                }
            }
            alert()->success('Setting are updated successfully', 'Success');
            return redirect()->back();
        }catch(\Exception $e){dd($e);
            alert()->error('Something Wrong.', 'Error');
            return redirect()->back();
        }
    }
}
