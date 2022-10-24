/// <reference path="../../../../../typings/main.d.ts" />
const ALPHABET = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

function generateUUID() {
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now(); //use high-precision timer if available
    }
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}

class Assessment {
    id:number;
    guid:string;
    title:string;
    instructions:string;
    cpd_hours:number;
    pass_percentage:number;
    time_limit_minutes:number;
    maximum_attempts:number;
    randomize_questions_order:boolean;
    questions:{[id: string]: Question};

    constructor(title = '', instructions = '', cpd_hours = 1, pass_percentage = 50, time_limit_minutes = 0, maximum_attempts = 0, randomize_questions_order = false) {
        this.id = 0;
        this.guid = generateUUID();
        this.title = title;
        this.instructions = instructions;
        this.cpd_hours = cpd_hours;
        this.pass_percentage = pass_percentage;
        this.time_limit_minutes = time_limit_minutes;
        this.maximum_attempts = maximum_attempts;
        this.randomize_questions_order = randomize_questions_order;
        this.questions = {};
    }

    addBlankQuestion() {
        var newQuestion = new Question('', this.nextSortOrder(), false);
        newQuestion.addBlankOption();
        this.addQuestion(newQuestion);
    }

    addQuestion(question) {
        var assessment = this;
        Vue.set(this.questions, question.guid, question);
        if (!question.collapsed) {
            if (_.size(assessment.questions) > 1) {
                this.collapseAllQuestionsExcept(question);
                question.expand();
            }
            else
                question.initSummernote();
        }
    }

    removeQuestion(removeQuestion) {
        Vue.delete(this.questions, removeQuestion.guid);
        this.reOrderAllQuestions();
    }

    nextSortOrder() {
        return _.size(this.questions) + 1;
    }

    collapseAllQuestions() {
        _.forEach(this.questions, function (question, questionKey) {
            question.collapse();
        });
    }

    collapseAllQuestionsExcept(questionToKeepExpanded) {
        _.forEach(this.questions, function (question, questionKey) {
            if (questionToKeepExpanded.guid != question.guid)
                question.collapse();
        });
    }

    expandAllQuestions() {
        _.forEach(this.questions, function (question, questionKey) {
            question.expand();
        });
    }

    reOrderAllQuestions() {
        var assessment = this;
        var sortedQuestions = _.sortBy(this.questions, 'sort_order');
        var index = 1;
        _.forEach(sortedQuestions, function (question, questionKey) {
            Vue.set(assessment.questions[question.guid], 'sort_order', index);
            index++;
        });
    }

    questionsLength() {
        return _.size(this.questions);
    }
}

class Question {
    id:number;
    guid:string;
    description:string;
    sort_order:number;
    options:{ [id: string]: AnswerOption };
    collapsed:boolean;

    constructor(description = '', sort_order = 0, collapsed = true) {
        this.id = 0;
        this.guid = generateUUID();
        this.description = description;
        this.sort_order = sort_order;
        this.options = {};
        this.collapsed = collapsed;
    }

    nextOptionSymbol() {
        var optionsLength = _.size(this.options);
        if (optionsLength <= 0)
            return ALPHABET[0];

        return ALPHABET[optionsLength];
    }

    addBlankOption() {
        var newOption = new AnswerOption(this.nextOptionSymbol());
        this.addOption(newOption);
    }

    addOption(option) {
        Vue.set(this.options, option.guid, option);
    }

    removeOption(removeOption) {
        Vue.delete(this.options, removeOption.guid);
        this.reSymbolAllOptions();
    }

    setCorrectOption(correctOption) {
        var question = this;
        _.forEach(question.options, function (option, optionKey) {
            option.is_correct = option.guid == correctOption.guid;
        });
    }

    initSummernote() {
        var question = this;
        Vue.nextTick(function () {
            $('#question-id-' + question.guid).summernote({
                minHeight: 40,
                placeholder: 'Question',
                focus: true,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol']]
                ],
                callbacks: {
                    onChange: function (contents, $editable) {
                        Vue.set(question, 'description', contents);
                    }
                }
            });
        });
    }

    destroySummernote() {
        var question = this;
        Vue.nextTick(function () {
            $('#question-id-' + question.guid).summernote('destroy');
        });
    }

    toggleCollapsed() {
        this.collapsed = !this.collapsed;
        if (this.collapsed)
            this.destroySummernote();
        else
            this.initSummernote();
    }

    collapse() {
        this.collapsed = true;
        this.destroySummernote();
    }

    expand() {
        this.collapsed = false;
        this.initSummernote();
    }

    reSymbolAllOptions() {
        var question = this;
        var sortedOptions = _.sortBy(question.options, 'symbol');
        var index = 0;
        _.forEach(sortedOptions, function (option, optionKey) {
            Vue.set(question.options[option.guid], 'symbol', ALPHABET[index]);
            index++;
        });
    }
}

class AnswerOption {
    id:number;
    guid:string;
    symbol:string;
    description:string;
    is_correct:boolean;

    constructor(symbol, description = '', is_correct = false) {
        this.id = 0;
        this.guid = generateUUID();
        this.symbol = symbol;
        this.description = description;
        this.is_correct = is_correct;
    }
}

Vue.component('assessments-admin-create-edit', {
    props: {
        assessmentJson: {
            default: function () {
                return {};
            }
        }
    },

    ready: function () {
    },

    data: function () {
        var vm = this;
        var ajaxStates = {
            none: 0,
            busy: 1,
            success: 2,
            error: 3
        };
        return {
            assessment: vm.createAssessmentFromJson(vm.assessmentJson),
            ajaxStates: ajaxStates,
            saveState: ajaxStates.none
        };
    },

    computed: {},

    methods: {
        createAssessmentFromJson: function (json) {
            var assessment = new Assessment();
            if (_.size(json) > 0) {
                assessment = new Assessment(json.title, json.instructions, json.cpd_hours, json.pass_percentage, json.time_limit_minutes, json.maximum_attempts, json.randomize_questions_order);
                assessment.id = json.id;
                assessment.guid = json.guid;
            }
            if (_.has(json, 'questions')) {
                _.forEach(json.questions, function (jsonQuestion, jsonQuestionKey) {
                    var question = new Question(jsonQuestion.description, jsonQuestion.sort_order, true);
                    question.id = jsonQuestion.id;
                    question.guid = jsonQuestion.guid;
                    if (_.has(jsonQuestion, 'options')) {
                        _.forEach(jsonQuestion.options, function (jsonOption, jsonOptionKey) {
                            var option = new AnswerOption(jsonOption.symbol, jsonOption.description, jsonOption.is_correct);
                            option.id = jsonOption.id;
                            option.guid = jsonOption.guid;
                            question.addOption(option);
                        });
                    }
                    else {
                        question.addBlankOption();
                    }
                    assessment.addQuestion(question);
                });
            }
            //else {
            //    assessment.addBlankQuestion();
            //}
            return assessment;
        },
        save: function () {
            var vm = this;
            vm.saveState = vm.ajaxStates.busy;
            vm.$http({
                url: '/admin/assessments/store',
                method: 'POST',
                data: {
                    'assessment': vm.assessment
                }
            }).then(
                function (response) {
                    vm.saveState = vm.ajaxStates.success;
                    swal({
                        title: "Success",
                        text: "Assessment has been saved.",
                        type: "success",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    if (response.data.assessment_id && response.data.assessment_id != vm.assessment.id)
                        setTimeout(function () {
                            window.location.href = '/admin/assessments/' + response.data.assessment_id + '/edit';
                        }, 1000);
                },
                function (response) {
                    vm.saveState = vm.ajaxStates.error;
                    swal('Error', 'Something went wrong, please check your internet and try again.', 'error');
                    console.log('error', response);
                }
            );
        }
    }
});