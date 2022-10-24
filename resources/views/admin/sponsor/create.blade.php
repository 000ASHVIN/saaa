@extends('admin.layouts.master')

@section('title', 'Create Sponsor')
@section('description', 'Create A New Sponsor')

@section('content') 
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'post', 'route' => 'admin.sponsor.store','files' => true]) !!}  
                         <div class="tabbable">
                            <ul class="nav nav-tabs" id="navigation-tabs">
                                <li class="active"><a data-toggle="tab" href="#sponsor">Sponsor</a></li>
                                <li class=""><a data-toggle="tab" href="#question">Question</a></li>
                            </ul>
                            <div class="tab-content">
                                @include('admin.sponsor.partials.form', ['button' => 'Create Sponsor'])
                                @include('admin.sponsor.partials.question', ['button' => 'Create Sponsor'])
                            </div>
                        </div>
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
                'url': '/admin/sponsor/preview',
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