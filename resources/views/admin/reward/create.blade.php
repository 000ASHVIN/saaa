@extends('admin.layouts.master')

@section('title', 'Create Email Template')
@section('description', 'Create A New Email Template')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'post', 'route' => 'admin.email_template.store']) !!}
                        @include('admin.email_template.partials.form', ['button' => 'Create Email Template'])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="/admin/assets/js/index.js"></script>
    <script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script>
        $('.is-date').datepicker;
        $('.select2').select2();
        $('textarea.ckeditorEmail').ckeditor();
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.extraAllowedContent = '*{*}';

        $(document).on('click','.emailpreview',function(){
            var email = $( '.ckeditorEmail' ).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url': '/admin/email_template/preview',
                'type': 'POST',
                'data': { email:email, _token: "{{ csrf_token() }}"},
                'success': function(data) {
                    $('#previewEmail').modal('show');
                    $('#previewEmail').find('.modal-body').html(data);
                    $('.modal').find('table[bgcolor]').each(function(){
                        $(this).attr('style','background:#'+$(this).attr('bgcolor'));
                    })
                },
                'error': function(){
                    console.error('Something wrong!');
                }
            });
        })
    </script>
@endsection