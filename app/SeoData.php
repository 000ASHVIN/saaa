<?php

namespace App;

use App\Traits\SEOTrait;
use Illuminate\Database\Eloquent\Model;

class SeoData extends Model
{
    use SEOTrait;
    protected $guarded = [];
    protected $table = 'seo_data';

    protected $Seoname = 'route';

    public function checkMetaTitle()
    {
        return $this->attributes['meta_title'];
    }
    public function getNameAttr()
    {
        return 'route';
    }

}
