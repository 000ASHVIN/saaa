<?php

namespace App\Traits;

trait SEOTrait
{
    /**
     * @return void
     */
    public function getName()
    {
        return $this->attributes[$this->Seoname];
    }

    public function getMetaTitle()
    {
        return $this->attributes['keyword'];
    }
    public function getNameAttr()
    {
        return 'slug';
    }
    

    public function getMetaDescriptionAttribute() {
        if($this->attributes['meta_description']!=""){
            return $this->attributes['meta_description'];
        }else{
			return  $this->attributes[$this->Seoname];
		}
    }
}