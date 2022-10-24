@extends('unsubscribe.layouts.master')
@section('description', 'Unsubscribe')
@section('content')
    <div>
        <h3><b>We’re sorry to see you go "{{ $user->first_name }}"</b></h3>
        <p>We've unsubscribed {{ $user->email }} from all future mailings from {{ config('app.name')}}.</p>
        {{-- <p>Want to give us one last chance? <a href="{{ route('resubscribe.email', $user->email) }}"><b>Click here to resubscribe</b></a> and only receive the emails you are interested in.</p> --}}
        <p>Alternatively, please take a minute to tell us why you no longer want to hear from us (select all that apply)</p>

        <form action="{{ route('unsubscribe.reason', $user->email) }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="reason[]" value="I receive too many emails"> I receive too many emails <i></i>
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="reason[]" value="I don’t think the content is relevant to me"> I don’t think the content is relevant to me <i></i>
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="reason[]" value="I don’t remember signing up to receive mailings"> I don’t remember signing up to receive mailings <i></i>
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="reason[]" value="I’m no longer interested in any products or services that the Tax Faculty offers"> I’m no longer interested in any products or services that the Tax Faculty offers <i></i>
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="reason[]" value="Other"> Other <i></i>
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit feedback</button>
            </div>
        </form>
    </div>
</body>
</html>
@endsection