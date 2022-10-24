@extends('admin.layouts.master')

@section('title', 'Assessments')
@section('description', 'Create')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.0/summernote.css" rel="stylesheet">
    <style type="text/css">
        .text-fade-out-right {
            cursor: pointer;
            position: absolute;
            width: 50px;
            left: -50px;
            top: 0;
            height: 28px;
            z-index: 1;
            background: -moz-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 52%); /* FF3.6-15 */
            background: -webkit-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 52%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 52%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00ffffff', endColorstr='#ffffff', GradientType=1); /* IE6-9 */
        }

        .collapsed-question-title {
            cursor: pointer;
        }

        .note-editor {
            padding-top: 0;
        }

        .note-toolbar.panel-heading {
            min-height: 0;
        }

        .input-group-addon.transparent-background {
            background-color: transparent;
            border-color: white;
        }

        .input-group-addon.no-padding {
            padding: 0;
        }

        input.form-control.minimal {
            border: 0;
            border-bottom: 1px solid #aeacb4;
        }

        .input-group-addon.symbol {
            background-color: #aeacb4;
            border-color: #aeacb4;
        }

        input[type="checkbox"].is-correct-checkbox {
            margin-right: 5px;
            margin-top: 2px;
            height: 28px;
            width: 30px;
        }

        .list-group-item.option {
            margin-bottom: 10px;
        }

        div.note-editor.note-frame.panel.panel-default {
            margin-bottom: 0;
        }

        .panel-tools {
            top: 5px;
        }

        .invisible {
            visibility: hidden;
        }

        .busy-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            opacity: 0.5;
            z-index: 99999999;
        }

        .busy-spinner {
            position: fixed;
            top: 40%;
            left: 49%;
            opacity: 1;
            z-index: 999999999;
        }

    </style>
@endsection

@section('content')
    <br>
    <div class="row">
        <div class="col-sm-12">
            <assessments-admin-create-edit
                    @if(isset($assessment))
                    :assessment-json="{{ $assessment->toJson() }}"
                    @endif
                    inline-template>
                <!-- Nav tabs -->
                <div v-cloak>
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="presentation" class="active"><a href="#assessment" aria-controls="home" role="tab"
                                                                  data-toggle="tab">Assessment</a></li>
                        <li role="presentation"><a href="#questions" aria-controls="profile" role="tab"
                                                   data-toggle="tab">Questions</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="assessment">
                            {!! Former::open()->method('POST')->route('admin.assessments.store') !!}
                            {!! Former::text('title','Title')->v_model('assessment.title') !!}
                            {!! Former::textarea('instructions','Instructions')->v_model('assessment.instructions') !!}
                            {!! Former::text('cpd_hours','CPD Hours')->v_model('assessment.cpd_hours') !!}
                            {!! Former::text('pass_percentage','Pass percentage')->v_model('assessment.pass_percentage') !!}
                            {!! Former::text('time_limit_minutes','Time limit (minutes)')->v_model('assessment.time_limit_minutes') !!}
                            {!! Former::text('maximum_attempts','Maximum attempts')->v_model('assessment.maximum_attempts') !!}
                            {!! Former::checkbox('randomize_questions_order','')->text('Randomize questions order')->v_model('assessment.randomize_questions_order') !!}
                            <button v-if="saveState != ajaxStates.busy" v-on:click="save()" type="button"
                                    class="btn .btn-squared btn-primary">Save
                            </button>
                            {!! Former::close() !!}
                        </div>
                        <div role="tabpanel" class="tab-pane"
                             id="questions">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div v-for="(questionKey,question) in assessment.questions">
                                        <div style="padding: 0; min-height: 0;" class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5 class="pull-left" style="max-width: 92%; overflow: hidden;">
                                                    <span class="collapsed-question-title"
                                                          v-on:click.prevent="question.toggleCollapsed()"
                                                          v-if="question.collapsed"
                                                          style="max-height: 28px; display: inline-block; white-space: nowrap;">
                                                        @{{ question.sort_order }}.&nbsp;@{{{ question.description }}}
                                                    </span>
                                                    <span v-else>
                                                        @{{ question.sort_order }}.
                                                    </span>
                                                    </h5>
                                                    <div class="pull-right text-right" style="position: relative;">
                                                        <div v-on:click.prevent="question.toggleCollapsed()"
                                                             class="text-fade-out-right"></div>
                                                        <a v-on:click.prevent="question.toggleCollapsed()"
                                                           href="#"
                                                           class="btn btn-default btn-sm">
                                                            <i v-if="!question.collapsed" class="ti-minus"></i>
                                                            <i v-else class="ti-plus"></i>
                                                        </a>
                                                        <button type="button"
                                                                v-on:click.prevent="assessment.removeQuestion(question)"
                                                                class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div v-if="!question.collapsed" class="col-sm-12">
                                                    <div id="question-id-@{{ question.guid }}"
                                                         data-question-id="@{{ question.guid }}"
                                                         class="question-description"
                                                         v-html="question.description">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="!question.collapsed" class="panel-wrapper in collapse">
                                            <div class="panel-body">
                                                <div v-for="(optionKey,option) in question.options"
                                                     class="list-group"
                                                     style="margin-bottom: 0;">
                                                    <div class="list-group-item option">
                                                        <div class="input-group">
                                                        <span class="input-group-addon transparent-background no-padding">
                                                                <input class="is-correct-checkbox"
                                                                       v-on:change="question.setCorrectOption(option)"
                                                                       type="checkbox" v-model="option.is_correct">
                                                        </span>
                                                        <span class="input-group-addon symbol">
                                                            @{{ option.symbol }}
                                                        </span>
                                                            <input type="text"
                                                                   class="form-control minimal border-bottom-on-focus"
                                                                   v-model="option.description">
                                                            <span class="input-group-btn">
                                                                <button v-on:click.prevent="question.removeOption(option)"
                                                                        type="button" class="btn btn-block btn-danger">
                                                                    <i class="fa fa-trash"></i></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button v-on:click="question.addBlankOption()" type="button"
                                                        class="btn btn-o .btn-squared btn-success">
                                                    Add option
                                                </button>
                                            </div>
                                        </div>
                                        <hr style="margin-top: 5px;">
                                    </div>
                                    <div v-if="assessment.questionsLength() == 0">
                                        <p>There are no questions for this assessment.</p>
                                    </div>
                                    <button v-on:click="assessment.addBlankQuestion()" type="button"
                                            class="btn btn-o .btn-squared btn-primary">Add
                                        question
                                    </button>
                                    <button v-if="saveState != ajaxStates.busy" v-on:click="save()" type="button"
                                            class="btn .btn-squared btn-primary">Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="saveState == ajaxStates.busy" class="busy-overlay">
                        <p class="text-center busy-spinner">
                            <i class="fa fa-cog fa-spin fa-5x"></i>
                        </p>
                    </div>
                </div>
            </assessments-admin-create-edit>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.0/summernote.js"></script>
    <script>
        $('.note-codable').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

    </script>
    <script src="/assets/admin/vendor/lodash/lodash.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@endsection