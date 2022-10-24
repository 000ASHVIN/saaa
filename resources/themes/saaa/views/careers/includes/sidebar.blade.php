<div class="col-md-3 col-sm-3 col-xs-3">
    <img width="100%" src="/assets/frontend/images/logo-a.jpg" alt="Careers" style="border-top-right-radius: 10px; border-top-left-radius: 10px">
    <ul class="list-group col_half" id="tabs">
        @foreach($departments as $department)
            <li class="list-group-item default-colors" style="border-radius: 0px">
                <span class="badge label-default-color">{{count($department->jobs)}}</span>
                <a href="#{{$department->id}}" id="tab{{$department->id}}" role="tab" data-toggle="tab" aria-controls="{{$department->title}}">{{$department->title}}</a>
            </li>
        @endforeach
    </ul>
</div>