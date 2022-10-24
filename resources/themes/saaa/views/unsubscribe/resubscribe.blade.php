@extends('unsubscribe.layouts.master')
@section('description', 'Resubscribe')
@section('content')
    <div>
        <br>
        <p>We only want you to be hearing about what’s right for you.</p>
        <hr>
        <p><b>You are currently subscribed to the email types below. Please select the types that interest you.</b></p>

        <form action="{{ route('resubscribe.type', $user->email) }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" class="resubscribe" name="subscribed_types[]" value="Events and webinars"> 
                    <i></i>
                    <b>Events and webinars</b>
                    <br>
                    Receive updates about upcoming Tax Faculty online and in-person events.
                    
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" class="resubscribe" name="subscribed_types[]" value="Short courses and Professional certificates"> 
                    <i></i>
                    <b>Short courses and Professional certificates</b>
                    <br>
                    Get information about the latest short courses and professional certificates released by the Tax Faculty.
                    
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" class="resubscribe" name="subscribed_types[]" value="Special product offers and promotions"> 
                    <i></i>
                    <b>Special product offers and promotions</b>
                    <br>
                    Receive special offers and promotions for Tax Faculty services and products.
                    
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" class="resubscribe" name="subscribed_types[]" value="Fund a student"> 
                    <i></i>
                    <b>Fund a student</b>
                    <br>
                    Receive communication to fund a student in need.
                    
                </label>
            </div>

            <hr>
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="unsubscribe_all" id="unsubscribe_all"> 
                    <i></i>
                    <b>Unsubscribe from all marketing emails.</b>
                    <br>
                    Check this box to completely unsubscribe from all marketing emails from us. Please note that if you are a CPD subscribers you may still receive solely transactional customer communication relevant to your services subscribed to.
                    
                </label>
            </div>
            <hr>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Preferences</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#unsubscribe_all').click(function() {
                if($(this).is(":checked")) {
                    $('.resubscribe').prop('checked', false);
                }
            });

            $('.resubscribe').click(function() {
                if($(this).is(":checked")) {
                    $('#unsubscribe_all').prop('checked', false);
                }
            });
        })
    </script>
@endpush