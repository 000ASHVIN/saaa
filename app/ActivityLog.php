<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = [];
    protected $table = 'activity_log';

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    public function productable(){
        return $this->morphTo('model','model','model_id');   
    }

    public function getString()
    {
        $model = explode("\\",$this->model);
        if($this->productable && strpos(get_class($this->productable),'Invoice') > 0){
            $items = '';
            if(($this->productable) && $this->productable->items){

                $items = implode(",", $this->productable->items->pluck('name')->toArray());
            }
            $item = ($this->productable)? ($this->productable->items)? $items:"":"";
            return $this->user->first_name." ". $this->action." ". end($model)." ".$item ;
        }else{	
            $name =($this->productable)? ($this->productable->name)?$this->productable->name:"":"";
            if($name == ''){
            $name =($this->productable)? ($this->productable->title)?$this->productable->title:"":"";
            }
            if($name == ''){
                $name =($this->productable)? ((in_array("App\Traits\SEOTrait",class_uses($this->productable)))
                && @$this->productable->getName())?@$this->productable->getName():"":"";
            }
            return  $this->user->first_name." ".$this->action." ".end($model)." ".$name;
        }
    }

    public function activityData() {
        $data = new \StdClass();
        if($this->data) {
            $data = json_decode($this->data);
        }
        return $data;
    }
}
