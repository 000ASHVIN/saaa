<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 4/25/2017
 * Time: 1:25 PM
 */

namespace App\Repositories\LiveWebinars;


use App\LiveWebinar;

class LiveWebinarRepository
{
    /**
     * @var LiveWebinar
     */
    private $liveWebinar;

    public function __construct(LiveWebinar $liveWebinar)
    {
        $this->liveWebinar = $liveWebinar;
    }

    public function all()
    {
        return $this->liveWebinar->paginate(10);
    }

    public function create($request)
    {
        $webinar =  $this->liveWebinar->create($request->except('_token', 'event'));
        $webinar->events()->sync(! $request->event ? [] : $request->event);
    }

    public function show($webinar)
    {
        return $this->liveWebinar->where('slug', $webinar)->first();
    }

    public function find($slug)
    {
        return $this->liveWebinar->where('slug', $slug)->first();
    }

    public function update($slug, $request)
    {
        $webinar = $this->find($slug);
        $webinar->slug = null;
        $webinar->update($request->except('_token', 'event'));
        $webinar->events()->sync(! $request->event ? [] : $request->event);
        return $webinar;
    }
}