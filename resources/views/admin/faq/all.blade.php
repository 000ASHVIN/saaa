@extends('admin.layouts.master')

@section('title', 'Frequently asked questions (FAQ)')
@section('description', 'All Questions and Answers listed here')

@section('styles')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
@stop

@section('content')
    <section>
        <br>
        <div class="container">
            {!! Form::open(['method' => 'get', 'route' => 'faq.all']) !!}
                {!! Form::input('hidden', 'search', '1') !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('question')) has-error @endif">
                            {!! Form::label('question', 'Search FAQs') !!}
                            {!! Form::input('text', 'question', request('question')?request('question'):null, ['class' => 'form-control']) !!}
                            @if ($errors->has('question')) <p class="help-block">{{ $errors->first('question') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if ($errors->has('categories')) has-error @endif">
                            {!! Form::label('categories', 'Category') !!}
                            {!! Form::select('categories', $categories->pluck('title', 'id'), request('categories')?request('categories'):null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder'=>'Select Category']) !!}
                            @if ($errors->has('categories')) <p class="help-block">{{ $errors->first('categories') }}</p> @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group @if ($errors->has('from_date')) has-error @endif">
                            {!! Form::label('from_date', 'From Date') !!}
                            {!! Form::input('text', 'from_date', request('from_date')?request('from_date'):null, ['class' => 'form-control is-datepicker', 'autocomplete' => 'off']) !!}
                            @if ($errors->has('from_date')) <p class="help-block">{{ $errors->first('from_date') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group @if ($errors->has('to_date')) has-error @endif">
                            {!! Form::label('to_date', 'To Date') !!}
                            {!! Form::input('text', 'to_date', request('to_date')?request('to_date'):null, ['class' => 'form-control is-datepicker', 'autocomplete' => 'off']) !!}
                            @if ($errors->has('to_date')) <p class="help-block">{{ $errors->first('to_date') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" onclick="spin(this)"  style="top: 22px;"><i class="fa fa-search" ></i> Search</button>
                        @if (request('search'))
                            <a href="{{ route('faq.all') }}" class="btn btn-default"  style="top: 22px;"><i class="fa fa-times" ></i> Clear</a>
                        @endif
                    </div>
                </div>
            {!! Form::close() !!}

            </div>
            <table class="table">
                <thead>
                    <th width="50%">Question</th>
                    <!-- <th>Show</th> -->
                    <th class="text-center">Tag</th>
                    <th class="text-center">Category</th>
                    <th>Date Created</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </thead>
                    <tbody>
                        @foreach($faqQuestion as $question)
                        
                            <tr>
                                <td>{{ str_limit($question->question, 160)  }}</td>

                                @if(count($question->faq_tags))
                                    <td class="text-center">{{ implode(', ',$question->faq_tags->pluck('title')   ->toArray())}}</td>
                                @else 
                                    <td class="text-center">-</td>
                                @endif
                                
                                @if(count($question->categories))
                                    <td class="text-center">{{ implode(', ',$question->categories->pluck('title')->toArray())}}</td>
                                @else
                                    <td class="text-center">-</td>
                                @endif

                                @if(count($question->date))
                                    <td>
                                        {{ date('d F Y',strtotime($question->created_at)) }}
                                    </td>
                                @else
                                    <td class="text-center">-</td>
                                @endif

                                <!-- <td><a href="{{ route('faq.questions_edit', $question->id) }}" target="_blank">Show</a></td> -->
                                <td><a href="{{ route('faq.questions_edit', $question->id) }}" >Edit</a></td>
                                <td><a class="confirm-delete" href="{{ route('faq.questions_remove', $question->id) }}">Remove</a></td>
                            </tr>
                            @include('admin.faq.includes.show_answer')
                        @endforeach
                    </tbody>
            </table>
        </div>
        <div class="text-center">
            @if(count($faqQuestion))
                <!-- {!! $faqQuestion->render() !!} -->
                {!! $faqQuestion->appends(Input::except('page'))->render() !!}
            @endif
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();

            // Initialize datepicker
            $('.is-datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

            // Initialize select2
            $('.select2').select2();
        });
    </script>
@stop