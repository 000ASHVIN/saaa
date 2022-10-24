<div class="links">
    <div class="row">
        <div class="col-sm-12">
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