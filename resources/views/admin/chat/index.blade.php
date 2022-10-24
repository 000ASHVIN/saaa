@extends('admin.layouts.master')

@section('title', 'Current Chats')
@section('description', 'List of Chats')

@section('styles')

<style>
    
</style>
@endsection

@section('content')
    <section class="">
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class=" panel-white col-sm-12">
                    <chat-list :user="{{ auth()->user() }}" inline-template>
                        <table class="table table-striped" id="sample-table-2">
                            <thead>
                                <th>Results</th>
                                <th>Full Name</th>
                                <th></th>
                                <th>Action</th>
                            </thead> 
                            <br>
                            <tbody>
                                <tr v-for="room in rooms">
                                    <td><span class="label label-body">@{{ room.index }}</span></td>
        
                                    <td>@{{ room.name }}</td>
        
                                    <td>
                                        <div class="label label-success" v-if="room.unseen > 0">@{{ room.unseen }}</div>
                                    </td>
    
                                    <td>
                                        <a class="btn btn-default" target="_blank" href="/admin/chat/chatbox/@{{ room.id }}">Chat Now</a>
                                    </td>
                                </tr>
                            </tbody> 
                        </table>
                    </chat-list>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    {{-- <script src="/admin/assets/js/index.js"></script> --}}
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop