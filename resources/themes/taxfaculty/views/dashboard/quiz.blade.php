@extends('app')

@section('title')
@stop

@section('content')
    <section>
        <div class="container">
                    <div class="row">
                        <form method="get" name="quiz">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="box-style-2 white-bg text-left">
                                        <div class="col-md-12">
                                            <h3>Quiz Title Goes Here</h3>

                                            <p>Quiz Description: </p>
                                            <p>
                                                Light green, sleek hair slightly reveals a sculpted, cheerful face. Dead hazel
                                                eyes, set
                                                wickedly within their sockets, watch eagerly over the castle they've stood guard
                                                for for
                                                so long.
                                            </p>
                                            <hr/>
                                        </div>

                                        <div class="space-bottom"></div>
                                        <div class="tab-content quiz-tabs">
                                            <div class="tab-pane active fadeIn " id="question1">
                                                <div class="col-md-12">
                                                    <p>Question:</p>
                                                    <p>What would you do if you couldn't use the internet or watch TV for a month?</p>
                                                    <small>Please select one of the following: ( Single Select )</small>
                                                    <div class="space-bottom"></div>
                                                    <br>
                                                </div>
                                              <p><label class="checkbox-inline"><input name="question_1" type="radio" value=""> Option 1</label></p>
                                              <p><label class="checkbox-inline"><input name="question_1" type="radio" value=""> Option 2</label></p>
                                              <p><label class="checkbox-inline"><input name="question_1" type="radio" value=""> Option 3</label></p>
                                            </div>

                                            <div class="tab-pane" id="question2">
                                                <div class="col-md-12">
                                                    <p>Question:</p>
                                                    <p>Do you read reviews about a movie before deciding whether to watch it or not?</p>
                                                    <small>Please select your answers below: ( Multiple Select )</small>
                                                    <div class="space-bottom"></div>
                                                    <br>
                                                    <p><label class="checkbox-inline"><input name="question_1" type="checkbox" value=""> Option 1</label></p>
                                                    <p><label class="checkbox-inline"><input name="question_1" type="checkbox" value=""> Option 2</label></p>
                                                    <p><label class="checkbox-inline"><input name="question_1" type="checkbox" value=""> Option 3</label></p>
                                                </div>
                                            </div>



                                            <div class="tab-pane clearfix" id="results">
                                                <div class="col-md-12">
                                                    <p>Your Quiz results: </p>
                                                    <p><strong>Congratulations</strong>, you have scored 90% on your quiz!</p>
                                                    <br/>

                                                    <div class="col-md-8 text-left">
                                                        <p>Total Questions: 10</p>
                                                        <p>Total Score: 10/10</p>
                                                        <p>Your Score: 9/10 </p>
                                                        <p>Passing Score: 80%</p>
                                                    </div>

                                                    <div class="col-md-4">
                                                        {{--If Success--}}
                                                        <i class="fa fa-trophy" style="font-size: 190px; opacity: 0.1;"></i>
                                                        {{--If Failure--}}
                                                        {{--<i class="fa fa-close" style="font-size: 190px; opacity: 0.1;"></i>--}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 clearfix quiz-buttons">
                                                    <div class="text-center">
                                                        <input type="button" class="btn btn-default" value="Previous">
                                                        <input type="button" class="btn btn-default" value="Next">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h4>Your Progress</h4>

                                    <div class="progress">
                                         <div class="progress-bar" role="progressbar" aria-valuenow="70"
                                         aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                           70%
                                         </div>
                                   </div>
                                    <br/>
                                    {{--Add More Tabs if needed--}}
                                    <div style="overflow-y: scroll; height: 368px;">
                                        <ul class="nav nav-pills nav-stacked" id="question" style="width: 100%">
                                            <li class="active"><a href="#question1" data-toggle="tab">Question 1</a></li>
                                            <li><a href="#question2" data-toggle="tab">Question 2</a></li>
                                            <li><a href="#results" data-toggle="tab"><i class="fa fa-trophy"></i> QuiZ Results</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </Section>
        @stop

@section('scripts')

@stop