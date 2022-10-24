@extends('admin.layouts.master')

@section('title', 'Show Industry')
@section('description', '')

@section('styles')
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-left" style="margin-bottom: 10px">
                            <a href="{{ route('admin.industries.index') }}" class="btn btn-info">Back</a>
                        </div>
                    </div>
                    
                </div>
                <div class="tab-content">
                    @include('admin.industries.partials.overview')
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
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection