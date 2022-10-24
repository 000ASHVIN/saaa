<?php

namespace App\Http\Controllers\Dashboard;

use App\Assessment;
use App\Assessments\Answer;
use App\Assessments\Attempt;
use App\Certificate;
use App\Users\Cpd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppEvents\Ticket;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Assessments\Option;

class AssessmentsController extends Controller
{
    public function show($id)
    {
        $assessment = Assessment::with(['questions', 'questions.options'])->findOrFail($id);
        $user = auth()->user();
        $attempts = $assessment->failedAttemptsForUser($user)->count();

        //Check if has access to assessment
        //TODO This

        //Check already passed
        if ($assessment->hasBeenPassedByUser($user)) {
            alert()->error('You have already passed that assessment', 'Error');
            return redirect()->route('dashboard');
        }

        //Check maximum attempts
        $maxAttempts = $assessment->maximum_attempts;
        if ($maxAttempts > 0 && $attempts >= $maxAttempts) {
            alert()->error('You have reached the maximum number of attempts (' . $assessment->maximum_attempts . ') for that assessment.', 'Error');
            return redirect()->route('dashboard');
        }

        //Remove is_correct field so that javascript does not know answers
        foreach ($assessment->questions as $question) {
            $question->options->transform(function ($option, $optionKey) {
                unset($option['is_correct']);
                return $option;
            });
        }

        return view('dashboard.assessment', compact('assessment', 'attempts', 'maxAttempts'));
    }

    public function lastResult($ticketId, $id)
    {
        $assessment = Assessment::findOrFail($id);
        $ticket = Ticket::with(['event'])->findOrFail($ticketId);
        $user = auth()->user();
        $attempt = $assessment->attempts()->where('user_id',$user->id)->latest()->first();
        if($attempt)
        {
            $result = $attempt->passed;
            $cpd = null;

            if($result) {
                $cpdIds = Cpd::where('user_id', $user->id)->where('source', 'like', '%'.$assessment->title)->get()->pluck('id')->toArray();
                $certificate = Certificate::where('source_model', get_class(new Assessment()))->where('source_id', $assessment->id)->whereIn('cpd_id', $cpdIds)->orderBy('created_at', 'desc')->first();
                if($certificate) {
                    $cpd = Cpd::find($certificate->cpd_id);
                }
            }
            $answers = Answer::with(['question','option'])->where('answers.attempt_id',$attempt->id)->get();

            $selectedAnswers = [];
            $alphabet = range('a', 'z');
            foreach($answers as $key => $answer) {
                $selectedAnswers[$key]['question'] = $answer->question->description;

                $options = Option::where('question_id', $answer->question->id)->get();
                foreach($options as $index => $option) {
                    if($option->id == $answer->option->id) {
                        $selectedAnswers[$key]['option'] = strtolower($option->symbol).') '.$option->description;
                        $selectedAnswers[$key]['result'] = $option->is_correct == 1 ? 'Correct' : 'Incorrect';
                    }
                }
            }
            return view('dashboard.last_result', compact('selectedAnswers','result', 'ticket', 'assessment', 'cpd'));
        }
        else
        {
            alert()->error('You have not attempted for these assessment yet !!', 'Error');
            return redirect()->back();
        }
        
    }

    public function mark(Requests\MarkAssessmentRequest $request, $id)
    {
        $assessment = Assessment::with(['questions', 'questions.correctOptions'])->findOrFail($id);
        $user = auth()->user();
        $attempts = $assessment->failedAttemptsForUser($user)->count();

        //Check already passed
        if ($assessment->hasBeenPassedByUser($user)) {
            return response()->json(['error' => 'You have already passed this assessment.'], 500);
        }

        //Check maximum attempts
        $maxAttempts = $assessment->maximum_attempts;
        if ($maxAttempts > 0 && $attempts >= $maxAttempts) {
            return response()->json(['error' => 'Maximum attempts exceeded.'], 500);
        }

        $passed = false;
        $answers = $request->get('answers', []);
        $results = [];
        $questions = collect($assessment->questions)->keyBy('guid');
        $questions->transform(function ($question, $questionKey) {
            $question->correctOptions = $question->correctOptions->keyBy('id');
            return $question;
        });
        $correctQuestions = 0;

        $attempt = $assessment->addAttemptForUser($user);
        foreach ($answers as $answer) {
            if (!$answer['option'] || $answer['option'] == null)
                continue;

            $attempt->addAnswer(new Answer([
                'question_id' => $answer['question']['id'],
                'option_id' => $answer['option']
            ]));
            $questionGuid = $answer['question']['guid'];
            $isCorrect = false;
            if ($questions->get($questionGuid)->correctOptions->has($answer['option'])) {
                $correctQuestions++;
                $isCorrect = true;
            }
            $results[$questionGuid] = $isCorrect;
        }

        $certificateUrl = null;
        $percentage = round(($correctQuestions / count($questions)) * 100);
        $attempt->percentage = $percentage;
        if ($percentage >= $assessment->pass_percentage) {
            $passed = true;
            $attempt->passed = true;
            $attempt->save();

            $event = $assessment->events()->first();
            $ticket = ($event)?$event->tickets()->where('user_id', $user->id)->first():0;

            $cpd = Cpd::create([
                'user_id' => $user->id,
                'date' => Carbon::now(),
                'hours' => $assessment->cpd_hours,
                'source' => 'Assessment: ' . $assessment->title,
                //TODO remove after push
                'has_certificate' => true,
                //TODO remove after push
                'ticket_id' => $ticket->id ?? 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $cpd->certificate()->create([
                'source_model' => Assessment::class,
                'source_id' => $assessment->id,
                'source_with' => ['passedAttempts', 'passedAttempts.user'],
                'view_path' => 'certificates.assessment'
            ]);
        }


        if ($passed)
            return [
                'passed' => $passed,
                'correctCount' => $correctQuestions,
                'percentage' => $percentage,
                'results' => $results,
                'certificateUrl' => route('dashboard.cpd.certificate', [$cpd->id])
            ];

        return [
            'passed' => $passed,
            'correctCount' => $correctQuestions,
            'percentage' => $percentage,
            'results' => $results
        ];
    }
}
