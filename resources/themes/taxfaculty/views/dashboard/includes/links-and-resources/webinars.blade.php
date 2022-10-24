<div class="webinars">
    <div class="row">
        <div class="col-sm-12">
            <div class="border-box clearfix text-center">
                <p> Please note that we are using click meeting for our live webinars, Please ensure that you have Google Chrome
                    installed on your device and that you are using Google Chrome when accessing a live webinar in order to avoid any
                    sound or image interuptions.
                </p>
                <div class="text-center">
                    <a href="https://goo.gl/Fn2dCf" target="_blank"><img style="width:5%" src="http://imageshack.com/a/img923/8992/W1tHZQ.jpg" alt="Google Chrome"> <strong>Download Now</strong></a>
                </div>
            </div>
            <hr>
            @if($webinars && count($webinars) > 0)
                @foreach($webinars as $webinar)
                    <div class="webinar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="single-webinar" style="padding: 10px; background-color: #f2f2f2">
                                    <img src="/assets/frontend/images/clickmeeting-logo.jpg" width="100%" alt="Adobe Connect"
                                         style="width: 100%" class="thumbnail">
                                    @if($webinar->passcode)
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">Password</span>
                                                <label>
                                                    <input style="font-weight: bold;" type="text" class="form-control"
                                                           value="{{ $webinar->passcode }}">
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    @if($webinar->is_active)
                                        <a href="{{ $webinar->url }}" target="_blank"
                                           class="btn btn-primary btn-block">
                                            <i class="fa fa-desktop"></i>Join Webinar
                                        </a>
                                    @else
                                        <button type="button" target="_blank"
                                                class="btn btn-primary btn-block disabled">
                                            <i class="fa fa-desktop"></i>Webinar Closed
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col-md-12">
                        <p>Webinar links not yet available.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>