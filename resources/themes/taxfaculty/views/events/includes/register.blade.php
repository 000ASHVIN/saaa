<div id="event_register" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="">
            <div class="modal-body text-center">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <a href="{!! route('events.register', $event->slug) !!}" class="btn btn-primary btn-block btn-xlg">
                            <b>Book Myself</b>
                            <span class="block font-lato">I would like to book for myself only</span>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <a href="#" class="btn btn-teal btn-block btn-xlg">
                            <b>Book my Team</b>
                            <span class="block font-lato">I would like to book my team</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
