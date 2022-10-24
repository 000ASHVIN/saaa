<div class="venue">
    {{--<div class="row">--}}
        {{--<div class="col-sm-12">--}}
            {{--<h4>Venue</h4>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="row">
        <div class="col-sm-12">
            <p>
                <strong>{{ $venue->name }}</strong>
                <br>{{ $venue->address_line_one }}
                @if($venue->address_line_two != '')
                    <br>{{ $venue->address_line_two }}
                @endif
                @if($venue->city != '')
                    <br>{{ $venue->city }}
                @endif
                @if($venue->area_code != '')
                    &nbsp;({{ $venue->area_code }})
                @endif
                @if($venue->province != '')
                    <br>{{ $venue->province }}
                @endif
                @if($venue->country != '' && $venue->country != 'South Africa')
                    <br>{{ $venue->country }}
                @endif
            </p>
        </div>
    </div>
</div>