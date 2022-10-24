@extends('app')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <h1>3D Secure</h1>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.6/css/bootstrap-dialog.min.css"/>
    <script src="/assets/frontend/js/bootstrap-dialog.min.js"></script>
    <script>
        $(function () {
            var dialog = new BootstrapDialog({
                message: '<iframe width="100%" height="500" src="/pusher-test-iframe"></iframe>',
                closable: false
            });
            dialog.realize();
            dialog.getModalHeader().hide();
            dialog.getModalFooter().hide();
            dialog.open();

            Pusher.log = function (message) {
                if (window.console && window.console.log) {
                    window.console.log(message);
                }
            };

            var pusher = new Pusher('260f91fd288125aabfd7', {
                encrypted: true
            });
            var channel = pusher.subscribe('3ds');
            channel.bind('App\\Events\\ThreeDSecureCompleted', function (data) {
                console.log(data);
                dialog.close();
            });
        });
    </script>
@endsection