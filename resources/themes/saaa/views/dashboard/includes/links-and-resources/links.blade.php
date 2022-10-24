<div class="links">
    <div class="row">
        <div class="col-sm-12">
            {{--<div class="row">--}}
                {{--<div class="col-sm-12">--}}
                    {{--<h4>Links</h4>--}}
                {{--</div>--}}
            {{--</div>--}}
            @if(count($links) > 0)
                <div class="panel-group" id="link">
                    @foreach($links as $month => $titles)
                        
                        <div id="this{{$month}}" class="panel-collapse collapse in">
                            <table class="table">
                                <thead>
                                <th>Name</th>
                                <th>Instructions</th>
                                <th class="text-center">Download</th>
                                </thead>
                                <tbody>
                                @foreach($titles as $title)
                                    <tr>
                                        <td><a href="{{ $title->url }}">{{ $title->name }}</a></td>
                                        <td>
                                            @if($title->instructions && $title->instructions != "")
                                                {!! $title->instructions !!}
                                            @endif
                                        </td>
                                        <td class="text-center"><a href="{{ $title->url }}"><i class="fa fa-download"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                   
                    @endforeach
                </div>

                {{--<div class="list-group" style="max-height: 704px; overflow-x: auto;">--}}

                         {{--@foreach($titles as $title)--}}
                             {{--{{ $title->name }} <br>--}}
                        {{--@endforeach--}}
                        {{--@endforeach--}}
                    {{--@foreach($links as $link)--}}

                        {{--{{ dd($link) }}--}}
                        {{--<a href="{{ $link->url }}" class="list-group-item">{{ $link->name }}--}}
                            {{--<span class="pull-right">--}}
                                {{--@if($link->instructions && $link->instructions != "")--}}
                                    {{--{!! $link->instructions !!}--}}
                                {{--@endif--}}
                            {{--</span>--}}
                        {{--</a>--}}
                    {{--@endforeach--}}
                {{--</div>--}}
                    {{--<div class="file">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-sm-12">--}}

                                {{--<a href="{{ $link->url }}" target="_blank"--}}
                                   {{--class="btn btn-default btn-block">--}}
                                    {{--<i class="fa fa-link"></i>&nbsp;{{ $link->name }}--}}
                                {{--</a>--}}
                                {{--<div class="text-center">--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <br>

            @else
                <div class="row">
                    <div class="col-sm-12">
                        <p> No links available at the moment.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>