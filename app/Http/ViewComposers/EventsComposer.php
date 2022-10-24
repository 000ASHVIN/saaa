<?php

namespace App\Http\ViewComposers;

use App\AppEvents\EventRepository;
use Illuminate\Contracts\View\View;

/**
* EventsComposer
*/
class EventsComposer
{
	protected $eventRepository;

	public function __construct(EventRepository $eventRepository)
	{
		$this->eventRepository = $eventRepository;
	}

	
	/**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if ($view->name() == 'includes.top-footer') {

            $events = $this->eventRepository->getPublished(3);

        } else if($view->name() == 'partials.uce') {

            $events = $this->eventRepository->getPublished(6);

        } else {
            $events = $this->eventRepository->getPublishedEvent();
        }

        $view->with('events', $events);
    }
}