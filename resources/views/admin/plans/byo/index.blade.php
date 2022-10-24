@extends('admin.layouts.master')

@section('title', 'Default BYO Features')
@section('description', 'Default Build Your Own Features')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        {!! Form::open() !!}
                        <label for="topics">Current Default Build Your Own Topics</label>
                        <select name="topics[]" id="topics" multiple="true" class="form-control select2">
                            @foreach($topics as $topic)
                                <option {{ ($default->contains('id', $topic->id)  ? "selected" : "") }} value="{{ $topic->id }}">{{ ucwords($topic->name) }}</option>
                            @endforeach
                        </select>

                        <hr>

                        <button type="submit" onclick="spin(this)" class="form-control btn btn-primary">Update Topics</button>
                        {!! Form::close() !!}
                    </div>
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
    <script type="text/javascript">
        $('.select2').select2();

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop