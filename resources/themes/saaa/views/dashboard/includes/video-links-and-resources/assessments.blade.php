<div class="assessments">
    <div class="row">
        <div class="col-sm-12">
            {{--<div class="row">--}}
                {{--<div class="col-sm-12">--}}
                    {{--<h4>Assessments</h4>--}}
                {{--</div>--}}
            {{--</div>--}}
            @if(count($assessments) > 0)
                <table class="table table-bordered">
                    <tbody>
                    @foreach($assessments as $assessment)
                        <tr>
                            <td valign="top">
                                <a href="{{ route('dashboard.assessments.show',[$assessment->id]) }}">
                                    {{ $assessment->title }}
                                </a>
                            </td>
                            <td valign="top">
                                {{ count($assessment->questions) }} question(s)
                            </td>
                            <td valign="top">
                                {{ $assessment->cpd_hours }} hour(s) CPD
                            </td>
                            <td valign="top">
                                @if(!$assessment->hasPassed())
                                    {{ $assessment->remainingAttempts() }} attempt(s) left
                                @else
                                    Passed
                                @endif
                            </td>
                            <td valign="top">
                                @if(!$assessment->hasPassed() && $assessment->remainingAttempts() > 0)
                                    <a href="{{ route('dashboard.assessments.show',[$assessment->id]) }}"
                                       class="btn btn-primary btn-xs">Start</a>
                                @elseif($assessment->hasPassed())
                                    {{ $assessment->passedAttempts(auth()->user())->first()->percentage }}%
                                @else
                                {{ ($assessment->getattemptsUser(auth()->user())->latest()->first())?$assessment->getattemptsUser(auth()->user())->latest()->first()->percentage:0 }}%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="row">
                    <div class="col-sm-12">
                        <p> No assessments available at the moment.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>