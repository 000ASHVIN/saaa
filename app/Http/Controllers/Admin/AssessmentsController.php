<?php

namespace App\Http\Controllers\Admin;

use App\Assessment;
use App\Assessments\Option;
use App\Assessments\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AssessmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assessments = Assessment::orderBy('created_at')->paginate(15);
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.assessments.create-edit');
    }

    public function store(Requests\CreateUpdateAssessmentRequest $request)
    {
        //Assessment update / create
        $assessmentJson = $request->get('assessment');
        $assessment = Assessment::findByGuid($assessmentJson['guid']);
        if ($assessment)
            $assessment->update($assessmentJson);
        else
            $assessment = Assessment::create($assessmentJson);

        //Questions update / create
        $questionsCollection = collect([]);
        if ($assessmentJson['questions'] && count($assessmentJson['questions']) > 0)
            $questionsCollection = collect($assessmentJson['questions'])->keyBy('guid');

        $questionsIds = $questionsCollection->pluck('guid')->toArray();

        //Existing questions
        $existingQuestions = $assessment->questions()->whereIn('guid', $questionsIds)->get()->keyBy('guid');
        foreach ($existingQuestions as $existingQuestion) {
            $question = collect($questionsCollection->get($existingQuestion->guid));
            $update = $question->only(Question::FILLABLE_FIELDS)->toArray();
            $update['description'] = trim($update['description']);
            $update['description'] = preg_replace('/\\s+/', ' ', $update['description']);
            $assessment->questions()->where('guid', $existingQuestion->guid)->update($update);

            //Options
            $optionsCollection = collect($questionsCollection->get($existingQuestion->guid)['options'])->keyBy('guid');
            $this->updateQuestionOptions($existingQuestion, $optionsCollection);
        }

        //New questions
        foreach ($questionsCollection as $question) {
            if (!$existingQuestions->has($question['guid'])) {
                $question = collect($question);
                $newQuestion = $question->only(Question::FILLABLE_FIELDS)->toArray();
                $newQuestion['description'] = trim($newQuestion['description']);
                $newQuestion['description'] = preg_replace('/\\s+/', ' ', $newQuestion['description']);
                $createdQuestion = $assessment->questions()->create($newQuestion);

                //Options
                $optionsCollection = collect($question['options'])->keyBy('guid');
                $this->updateQuestionOptions($createdQuestion, $optionsCollection);
            }
        }

        //Delete questions
        $allQuestions = $assessment->questions()->get()->keyBy('guid');
        $deletedQuestionIds = [];
        foreach ($allQuestions as $question) {
            if (!$questionsCollection->has($question['guid']))
                $deletedQuestionIds[] = $question->id;
        }
        $assessment->questions()->whereIn('id', $deletedQuestionIds)->delete();
        Option::whereIn('question_id', $deletedQuestionIds)->delete();

        return ['assessment_id' => $assessment->id];
    }

    public function updateQuestionOptions($question, $optionsCollection)
    {
        $optionIds = $optionsCollection->pluck('guid')->toArray();

        //Existing options
        $existingOptions = $question->options()->whereIn('guid', $optionIds)->get()->keyBy('guid');
        foreach ($existingOptions as $existingOption) {
            $update = collect($optionsCollection->get($existingOption->guid))->only(Option::FILLABLE_FIELDS)->toArray();
            $update['description'] = trim($update['description']);
            $update['description'] = preg_replace('/\\s+/', ' ', $update['description']);
            $question->options()->where('guid', $existingOption->guid)->update($update);
        }

        //New options
        foreach ($optionsCollection as $option) {
            if (!$existingOptions->has($option['guid'])) {
                $option = collect($option);
                $newOption = $option->only(Option::FILLABLE_FIELDS)->toArray();
                $newOption['description'] = trim($newOption['description']);
                $newOption['description'] = preg_replace('/\\s+/', ' ', $newOption['description']);
                $question->options()->create($newOption);
            }
        }

        //Delete questions
        $allOptions = $question->options()->get()->keyBy('guid');
        $deletedOptionIds = [];
        foreach ($allOptions as $option) {
            if (!$optionsCollection->has($option['guid']))
                $deletedOptionIds[] = $option['guid'];
        }
        $question->options()->whereIn('guid', $deletedOptionIds)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assessment = Assessment::with(['questions', 'questions.options'])->findOrFail($id);
        $description = collect();
        if($assessment->questions){
            $assessment->questions->each(function ($questions) use($description) {
            //    $questions->description= preg_replace(
            //     '(mso-[a-z:0-9-;,]*)',
            //     '',        // replace that match with nothing
            //     $questions->description
            // );
            // $questions->description = str_replace("<o:p></o:p>","",$questions->description);
            $questions->description = htmlspecialchars_decode ($questions->description);
           
        });
        }

        return view('admin.assessments.create-edit', compact('assessment'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
