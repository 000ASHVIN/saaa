@extends('admin.layouts.master')

@section('title', 'FAQ Categories')
@section('description', 'All Categories')

@section('content')
<section>
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('faq.categories.link') }}" class="btn btn-success"><i class="fa fa-plus"></i> Link Category</a>
                <hr>
                @if(count($arrCategories))
                    @foreach($arrCategories as $cat)
                        
                            <?php
                                $level = 0;
                            ?>
                            @include('admin.faq.accordion')
                    @endforeach 
                @endif
            </div>
        </div>
    </div>
</section>

@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop