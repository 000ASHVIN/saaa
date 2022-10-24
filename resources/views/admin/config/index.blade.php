@extends('admin.layouts.master')

@section('title', 'Setting')
@section('description', 'Show Available Settings')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::open(['method' => 'post', 'route' => ['admin.settings.store']]) !!}
            
            {!! Form::hidden('remove_ids', "", ['id'=>'removed_id']) !!}
            
            <div class="row">
                <div class="col-md-12">
                    <a href="javascript:void(0)" class="btn btn-primary addConfig">Add New</a>
                    <table class="table table-striped" id="settings">
                        <thead>
                            <th>Options</th>
                            <th>Value</th>
                            <th>Action</th>
                           
                        </thead>
                        <tbody>
                            @foreach($config as $configs)
                            <tr>
                                <td>
                                {!! Form::hidden('id[]', $configs->id,['class'=>'id_no']) !!}
                                {!! Form::text('options[]', $configs->options, ['class="form-control"']) !!}</td>
                                <td>{!! Form::textarea('value[]',  $configs->value, ['class="form-control"']) !!}</td>
                                <td><a href="javascript:void(0)" class="btn btn-primary removeConfig">Remove New</a> </td>
                                
                            </tr>
                            @endforeach
                            <tr>
                                    {!! Form::hidden('id[]', '',['class'=>'id_no']) !!}
                                    <td>{!! Form::text('options[]', '', ['class="form-control"']) !!}</td>
                                    <td>{!! Form::textarea('value[]', '', ['class="form-control"']) !!}</td>
                                    <td><a href="javascript:void(0)" class="btn btn-primary removeConfig">Remove New</a> </td>
                                    
                            </tr>

                        </tbody>
                        
                    </table>
                </div>
                {!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
                 <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
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
    <script>
        $(document).on('click','.addConfig',function(){
            $('#settings').find('tbody').find('tr:first').clone().appendTo('#settings tbody');
            $('#settings').find('tbody').find('tr:last').find('input').val('');
            $('#settings').find('tbody').find('tr:last').find('textarea').val('');
        })
        $(document).on('click','.removeConfig',function(){
            var value_id = $(this).closest('tr').find('.id_no').val();
            var removed_id = $('#removed_id').val();
            if(value_id!=""){
                removed_id = removed_id + ","+value_id;
            }
            $('#removed_id').val(removed_id);
            $(this).closest('tr').remove();
        })
    </script>
@endsection