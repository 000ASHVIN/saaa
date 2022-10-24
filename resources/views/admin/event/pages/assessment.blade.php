<div id="assessment" class="tab-pane fade">
    @if(count($event->assessments))
        <table class="table table-bordered table-striped">
            <thead>
                <th>Title</th>
                <th>CPD Hours</th>
                <th>Pass Percentage</th>
                <th>Time Limit</th>
                <th>Maximum Attempts</th>
                <th>Randomize Questions</th>
                <th>Questions</th>
                </thead>
            <tbody>
            @foreach($event->assessments as $assessment)
                <tr>
                    <td>{{ $assessment->title }}</td>
                    <td>{{ $assessment->cpd_hours }}</td>
                    <td>{{ $assessment->pass_percentage }}</td>
                    <td>{{ $assessment->time_limit_minutes }}</td>
                    <td>{{ $assessment->maximum_attempts }}</td>
                    <td>{{ ($assessment->randomize_questions_order ? "Yes" : "No") }}</td>
                    <td>{{ count($assessment->questions) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <p>There is no Assessment for this event.</p>
        </div>
    @endif

    <hr>
    <div class="form-group">
        <button class="btn btn-info" data-toggle="modal" data-target="#assign_assessment">Assign New Assessment</button>
        @include('admin.event.includes.assessment.assign')
    </div>
</div>