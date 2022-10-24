<?php

namespace App\Store;

use App\Assessment;
use App\File;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use SearchableTrait;

    protected $table = 'store_products';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $searchable = [
        'columns' => [
            'topic' => 10,
        ]
    ];

    public static function search($search)
    {
        $search = strtolower(str_replace('/[^A-z0-9 ]/g', '', trim($search)));
        $searchFields = explode(" ", $search);
        $where = "LOWER(topic) like ? OR LOWER(title) like ? OR LOWER(year) like ?";
        $query = '';
        $queryParameters = [];
        $count = 0;
        $maxCount = count($searchFields);
        foreach ($searchFields as $searchField) {
            $query .= $where;
            $parameter = '%' . $searchField . '%';
            $queryParameters[] = [$parameter, $parameter, $parameter];
            if ($count < $maxCount - 1) {
                $query .= " AND ";
            }
            $count++;
        }
        $resultProducts = static::whereRaw($query, $queryParameters)
            ->with('listings')->get();
        return $resultProducts;
    }

    /*
     * Relationships
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'store_listing_store_product')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productListings()
    {
        return $this->hasMany(ProductListing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * @param $links
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function addLinks($links)
    {
        return $this->links()->saveMany($links);
    }

    /**
     * @param Link $link
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function addLink(Link $link)
    {
        return $this->addLinks([$link]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'file_store_product')->withTimestamps();
    }

    /**
     * @param $files
     * @return array
     */
    public function addFiles($files)
    {
        return $this->files()->saveMany($files);
    }

    /**
     * @param File $file
     * @return array
     */
    public function addFile(File $file)
    {
        return $this->addFiles([$file]);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'store_product_tag');
    }

    public function addTags($tags)
    {
        return $this->tags()->saveMany($tags);
    }

    public function addTag(Tag $tag)
    {
        return $this->addTags([$tag]);
    }

    public function getDetailedTitleAttribute()
    {
        $detailedTitle = $this->title;
        if ($this->topic)
            $detailedTitle = $this->topic . ': ' . $detailedTitle;
        if ($this->year)
            $detailedTitle = $detailedTitle . ' (' . $this->year . ')';

        return $detailedTitle;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class);
    }

    public function getAssessmentAttribute()
    {
        return $this->assessments->pluck('id')->toArray();
    }
}
