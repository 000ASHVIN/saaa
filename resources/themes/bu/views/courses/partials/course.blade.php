<div class="event-container-box clearfix">

    <div class="event-container-inner">
        <h4>{!! $course->title !!}</h4>

        <h5>
            <i class="fa fa-graduation-cap"></i> {{ date_format($course->start_date, 'd F Y') }} - {{ date_format($course->end_date, 'd F Y') }}
        </h5>

        <div class="venue-container">
            @foreach($course->tags as $tag)
                <li class="styledLi">{{ \App\Blog\Category::find($tag)->title }}</li>
            @endforeach
        </div>

        <p>{!! preg_replace('/[^a-zA-Z0-9 .]/', '', $course->short_description) !!}</p>

        <hr>

        <a href="{{ route('courses.show', $course->reference) }}" class="btn btn-default"><i class="fa fa-graduation-cap"></i> Apply Now</a>
        <a href="{{ route('courses.show', $course->reference) }}" class="btn btn-primary"><i class="fa fa-eye"></i> Read More</a>
    </div>
</div>