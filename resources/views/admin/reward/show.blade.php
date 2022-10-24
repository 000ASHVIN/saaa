@extends('admin.layouts.master')

@section('title', 'Show Email Template')
@section('description', str_limit($reward->title, 80))

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <style>
        @keyframes spinner {
            to {transform: rotate(360deg);}
        }
        .spinner:before {
            animation: rotate 2s linear infinite;
            background-color: transparent;
            content: '';
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100px;
            height: 100px;
            margin-top: -10px;
            margin-left: -10px;
            border-radius: 50%;
            border: 2px solid #ccc;
            border-top-color: #21679b;
            animation: spinner .6s linear infinite;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            
                <div class="tab-content">
                    @include('admin.reward.partials.overview')
                </div>

            
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/js/index.js"></script>

    <script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript">
        $('.select2').select2({
            placeholder: "Please select",
        });
        $('.is-date').datepicker;

        $.fn.modal.Constructor.prototype.enforceFocus = function () {
            var that = this;
            $(document).on('focusin.modal', function (e) {
                if ($(e.target).hasClass('select2')) {
                    return true;
                }
                if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                    that.$element.focus();
                }
            });
        };
    </script>

    <script>
        $(document).ready(function(){
            $('#navigation-tabs a[href="#{{old('tab')}}"]').tab('show')
            new Clipboard('#copy-button');
        })


        $(document).on('click','.emailpreview',function(){
            var email = $( '.ckeditorEmail' ).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url': '/admin/reward/preview',
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

    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }

        $('textarea.ckeditorEmail').ckeditor();
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.extraAllowedContent = '*{*}';

    </script>

    <!-- DataTables -->
   

@endsection