@extends('admin.layouts.master')

@section('title', 'Frequently asked questions (FAQ)')
@section('description', 'All "Tags/Categories" listed here')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="well">
                Please be <strong style="color: red">extremely careful</strong>, when removing a tag you will also delete all questions relating to this tag!
            </div>
            @include('admin.errors.validate')
            <table class="table table-hover">
                <thead>
                    <th>Tag Name</th>
                    <th>Type</th>
                    <th>Questions</th>
                    <th>Edit Tag</th>
                    <th>Remove</th>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{ ucwords($tag->title) }}</td>
                            <td>{{ ucwords(($tag->type ? : "N/A")) }}</td>
                            <td>{{ count(\App\FaqQuestion::where('faq_tag_id', '=', $tag->id)->get()) }}</td>
                            <td><a href="#tag{{$tag->id}}" data-toggle="modal">Edit Tag</a></td>
                            <td><a href="{{ route('faq.tags_destroy', $tag->id) }}">Remove Tag</a></td>
                            @include('admin.faq.includes.edit_tag')
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_tag">Create New</button>
            @include('admin.faq.includes.tags')
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