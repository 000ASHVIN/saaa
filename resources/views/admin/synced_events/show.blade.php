@extends('admin.layouts.master')

@section('title', 'Update Synced Event')
@section('description', 'Update Synced Event'.' '.$event->name. '<br/>'.' URL: '.'<a href="'.url().'/events/'.$event->slug.'">'.url().'/events/'.$event->slug.'</a>')

@section('styles')
    <link href="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <style>
        .daterangepicker{
            z-index: 10000 !important;
        }
        .select2-container--open{
            z-index:9999999
        }
        .popover.clockpicker-popover.bottom.clockpicker-align-left {
            z-index: 999999;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid container-fullw bg-white ng-scope">
        <div class="tabbable">
            <ul class="nav nav-tabs" id="navigation-tabs">
                <li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
                <li class=""><a data-toggle="tab" href="#pricings">Pricings</a></li>
                <li class=""><a data-toggle="tab" href="#venues">Venue's</a></li>
                <li class=""><a data-toggle="tab" href="#dates">Dates</a></li>
                <li class=""><a data-toggle="tab" href="#assessment">Assessments</a></li>
                <li class=""><a data-toggle="tab" href="#extra">Extra's</a></li>
                <li class=""><a data-toggle="tab" href="#files">Links</a></li>
                <li class=""><a data-toggle="tab" href="#discount">Discount</a></li>
                <li class=""><a data-toggle="tab" href="#webinars">Webinars</a></li>
            </ul>

            <div class="tab-content">
                @include('admin.synced_events.pages.overview')
                @include('admin.synced_events.pages.pricings')
                @include('admin.synced_events.pages.venues')
                @include('admin.synced_events.pages.dates')
                @include('admin.synced_events.pages.assessment')
                @include('admin.synced_events.pages.extra')
                @include('admin.synced_events.pages.files')
                @include('admin.synced_events.pages.discounts')
                @include('admin.synced_events.pages.webinars')
            </div>

        </div>
    </div>
    @include('admin.synced_events.includes.venues.create')
@endsection

@section('scripts')
    <script src="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script type="text/javascript">

        $('.select2').select2({
            placeholder: "Please select",
        });
        $('.is-date').datepicker;
        $('.timepicker').clockpicker();
        //fix modal force focus
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
    </script>

    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
        
        $('#overview input, #overview select, #overview textarea').prop('disabled', true)
    </script>
@endsection