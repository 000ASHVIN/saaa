@section('scripts')
    <script>
        ($)
        swal("Subscription Taken", "This subscription belongs to {{ $member->subscription('cpd')->SalesAgent()->first_name.' '.$member->subscription('cpd')->SalesAgent()->last_name }}", "warning", {
            button: "Aww yiss!",
        });
    </script>
@endsection