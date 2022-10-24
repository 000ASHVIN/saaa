/// <reference path="../../../../../typings/main.d.ts" />
Vue.component('assessment', {

    props: {
        assessment: {
            required: true
        },
        attempts: {
            type: Number,
            required: false,
            default: function () {
                return 0;
            }
        },
        maxAttempts: {
            type: Number,
            required: false,
            default: function () {
                return 0;
            }
        }
    },

    ready: function () {
    },

    data: function () {
        var vm = this;
        var navStates = {
            overview: 0,
            question: 1,
            results: 2
        };
        var resultStates = {
            unattempted: 0,
            correct: 1,
            incorrect: 2
        };
        var markingStates = {
            none: 0,
            busy: 1,
            success: 2,
            error: 3
        };
        var answers = {};
        _.forEach(vm.assessment.questions, function (question, questionKey) {
            answers[question.guid] = {
                question: question,
                option: null,
                result: resultStates.unattempted
            };
        });
        vm.assessment.questions = _.sortBy(vm.assessment.questions, 'sort_order');
        vm.assessment.questions = _.keyBy(vm.assessment.questions, 'guid');

        var canRetry = vm.checkIfCanRetry();
        return {
            answers: answers,
            navStates: navStates,
            navState: navStates.overview,
            markingStates: markingStates,
            markingState: markingStates.none,
            activeQuestion: null,
            hasBeenMarked: false,
            certificateUrl: null,
            passed: false,
            canRetry: canRetry,
            remainingAttempts: vm.calculateRemainingAttempts(),
            results: null,
            responseData: null,
        };
    },

    computed: {
        progressPercentage: function () {
            return this.calculateProgressPercentage();
        },
        canPrevious: function () {
            return this.checkIfCanPrevious();
        },
        canNext: function () {
            return this.checkIfCanNext();
        },
        canSubmit: function () {
            return this.checkIfCanSubmit();
        }
    },

    methods: {
        start: function () {
            var vm = this;
            vm.switchToQuestion(vm.firstQuestion().guid);
        },
        viewAnswers: function () {
            var vm = this;
            vm.switchToQuestion(vm.lastQuestion().guid);
        },
        questionsLength: function () {
            var vm = this;
            return _.size(vm.assessment.questions);
        },
        answersLength: function () {
            var vm = this;
            return _.size(vm.answers);
        },
        switchToOverview: function () {
            var vm = this;
            vm.navState = vm.navStates.overview;
            vm.activeQuestion = null;
        },
        switchToResults: function () {
            var vm = this;
            vm.navState = vm.navStates.results;
            vm.activeQuestion = null;
        },
        switchToQuestion: function (questionGuid) {
            var vm = this;
            vm.activeQuestion = vm.assessment.questions[questionGuid];
            vm.navState = vm.navStates.question;
        },
        questionByIndex(index)
        {
            var vm = this;
            var questionsKeys = _.keys(vm.assessment.questions);
            return vm.assessment.questions[questionsKeys[index]];
        },
        questionIndex(questionGuid)
        {
            var vm = this;
            var questionsKeys = _.keys(vm.assessment.questions);
            return _.indexOf(questionsKeys, questionGuid);
        },
        checkIfCanPrevious: function () {
            var vm = this;
            return vm.navState == vm.navStates.question && vm.questionIndex(vm.activeQuestion.guid) > 0;
        },
        previous: function () {
            var vm = this;
            if (vm.checkIfCanPrevious())
                vm.switchToQuestion(vm.previousQuestion().guid);
        },
        checkIfCanNext: function () {
            var vm = this;
            if (vm.navState == vm.navStates.overview)
                return true;

            return !!(vm.navState == vm.navStates.question && vm.questionIndex(vm.activeQuestion.guid) < vm.questionsLength() - 1);
        },
        next: function () {
            var vm = this;
            if (vm.navState == vm.navStates.overview)
                vm.switchToQuestion(vm.firstQuestion().guid);
            else if (vm.navState == vm.navStates.question && vm.checkIfCanNext())
                vm.switchToQuestion(vm.nextQuestion().guid);
            else
                vm.submit();
        },
        firstQuestion: function () {
            var vm = this;
            var questionsKeys = _.keys(vm.assessment.questions);
            return vm.assessment.questions[questionsKeys[0]];
        },
        lastQuestion: function () {
            var vm = this;
            var questionsKeys = _.keys(vm.assessment.questions);
            return vm.assessment.questions[questionsKeys[questionsKeys.length - 1]];
        },
        nextQuestion: function () {
            var vm = this;
            var currentIndex = vm.questionIndex(vm.activeQuestion.guid);
            var nextIndex = currentIndex + 1;
            if (nextIndex < vm.questionsLength())
                return vm.questionByIndex(nextIndex);

            return null;
        },
        previousQuestion: function () {
            var vm = this;
            var currentIndex = vm.questionIndex(vm.activeQuestion.guid);
            var previousIndex = currentIndex - 1;
            if (previousIndex >= 0)
                return vm.questionByIndex(previousIndex);

            return null;
        },
        calculateProgressPercentage: function () {
            var vm = this;
            var attemptedQuestions = 0;
            _.forEach(vm.answers, function (answer, questionGuid) {
                if (answer.option && answer.option != null)
                    attemptedQuestions++;
            });
            return Math.round((attemptedQuestions / vm.answersLength()) * 100);
        },
        checkIfCanSubmit: function () {
            var vm = this;
            return vm.calculateProgressPercentage() > 0;
        },
        submit: function () {
            var vm = this;
            var text = "Are you sure you want so submit?<br><br><em>You will not be able to change your answers after you submit.</em>";
            if (vm.progressPercentage < 100)
                text += "<br><br><strong>You still have unanswered questions!</strong>";

            swal({
                title: "Submit for marking",
                html: true,
                text: text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, submit",
                cancelButtonText: "No, go back"
            }, function () {
                vm.markingState = vm.markingStates.busy;
                vm.$http({
                    url: '/dashboard/assessments/' + vm.assessment.id + '/mark',
                    method: 'POST',
                    data: {
                        'answers': vm.answers
                    }
                }).then(
                    function (response) {
                        vm.markingState = vm.markingStates.success;
                        vm.switchToResults();
                        vm.results = response.data.results;
                        vm.responseData = response.data;
                        if (response.data.passed) {
                            swal('You passed!', 'Congratulations, you have been awarded ' + vm.assessment.cpd_hours + ' hour(s) of CPD.', 'success');
                            vm.hasBeenMarked = true;
                            vm.passed = true;
                            vm.certificateUrl = response.data.certificateUrl;
                        }
                        else {
                            swal('So close!', "Unfortunately you didn't get enough answers correct to pass.", 'error');
                            vm.hasBeenMarked = true;
                            vm.passed = false;
                        }
                    },
                    function (response) {
                        vm.markingState = vm.markingStates.error;
                        if (response.data.error)
                            swal('Error', response.data.error, 'error');
                        else
                            swal('Error', 'Something went wrong, please check your internet and try again.', 'error');
                        console.log('error', response);
                    }
                );

            });
        },
        calculateRemainingAttempts: function () {
            var vm = this;
            return vm.maxAttempts - vm.attempts;
        },
        checkIfCanRetry: function () {
            var vm = this;
            return vm.calculateRemainingAttempts() > 0 || vm.maxAttempts == 0;
        },
        retry: function () {
            var vm = this;
            if (vm.canRetry)
                window.location.reload();
        }
    }
});
