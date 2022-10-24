@extends('admin.layouts.master')

@section('title', 'Chat Dashboard')
@section('description', 'List of Chats')

@section('styles')
<style>
</style>
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class=" panel-white col-sm-12">
                    <table class="table table-striped" id="sample-table-2">
                        <thead>
                            <th>Results</th>
                            <th>Full Name</th>
                            <th></th>
                            <th>Action</th>
                        </thead> 
                        <br>
                        <tbody>
                        @if(count($chats)) 
                            <span style="display: none;">
                                {{ $i = 0 }}
                            </span>
                            @foreach($chats as $chat)
        
                          
                                @if($chat)
                                    <span style="display: none;">
                                        {{ $i++ }}
                                    </span>
                                    <tr>
                                        <td width="1px"><span class="label label-body">{!! $i !!}</span></td>
            
                                        <td>{{ $chat->user->first_name }}</td>
            
                                        <td>
                                            {{-- <div class="label label-success">{{ $chat->unseen }}</div> --}}
                                        </td>

                                        <td>
                                            <a class="btn btn-default" href="{{ route('admin.chatbox.read', $chat->id) }}">Read</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop