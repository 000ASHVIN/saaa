@if(!$edit)
    <create-edit-video :video-providers="{{ $videoProviders->toJson() }}" inline-template>
        @endif
        <div class="panel panel-default">
            @if (!$edit)
                <div class="panel-heading">
                    <div class="col-sm-12 col-md-8">
                        <h4>Details</h4>
                    </div>
                </div>
            @endif
            <div class="panel-body">
                @if(!$edit)
                    {!! Former::open()->route('admin.videos.store') !!}
                @else
                    {!! Former::open()->route('admin.videos.update',$video->id) !!}
                    {!! Former::populate($video) !!}
                @endif
                {!! Former::text('title','Title')->v_model('form.title') !!}
                {!! Former::text('reference','Reference')->v_model('form.reference') !!}
                {!! Former::select('video_provider_id','Video Provider')->options($videoProviders->pluck('title','id')->toArray())->v_model('form.videoProviderId') !!}

                {!! Former::select('category','Category',($categories ? ['' => 'Category'] + $categories->toArray() : ['' => 'Category']),null,['placeholder'=>'Category']) !!}

<?php
    if(!isset($subcategories)) {
        $subcategories = [];
    }
    $style = 'display:none;';
    if(count($subcategories)) {
        $style = '';
    }
?>

<div class="" style="{!! $style !!}" id="sub_cat">
    {!! Former::select('sub_category','Sub Category',['' => 'Select'] + $subcategories,null) !!}
</div>
<?php
    if(!isset($subsubcategories)) {
        $subsubcategories = [];
    }
    $style = 'display:none;';
    if(count($subsubcategories)) {
        $style = '';
    }
?>
<div class="" style="{!! $style !!}" id="sub_sub_cat">
    {!! Former::select('sub_sub_category','Sub Sub Category')->options(['' => 'Select'] + $subsubcategories) !!}
</div>

                {!! Former::checkbox('can_be_downloaded','')->text('Can be downloaded') !!}
                {!! Former::text('download_link','Download link')->v_model('form.downloadLink') !!}
                {!! Former::text('view_link','View link')->v_model('form.viewLink') !!}

                {!! Former::text('hours','CPD Hours')->v_model('form.hours') !!}

                {!! Former::select('tag','Video Tag')->options([
                    null => 'Please Select',
                    'studio' => 'Studio',
                    'webinar' => 'Webinar Recording',
                ]) !!}

                {!! Former::text('amount','Price')->v_model('form.amount') !!}

                <div class="form-group @if ($errors->has('presenters')) has-error @endif">
                    {!! Form::label('VideoPresentersList', 'Please Select Presenters') !!}
                    {!! Form::select('VideoPresentersList[]', $presenters,  (isset($video))?$video->presenters->pluck('id')->toArray():'', ['class' => 'select2 form-control', 'multiple' => true]) !!}
                    @if ($errors->has('presenters')) <p class="help-block">{{ $errors->first('presenters') }}</p> @endif
                </div>

                {!! Former::textarea('description')->class('ckeditor')->rows(10)->autofocus()->v_model('form.description'); !!}
                    
                <div class="form-group @if ($errors->has('features')) has-error @endif">
                    {!! Form::label('VideoFeaturesList', 'Please select your features') !!}
                    {!! Form::select('VideoFeaturesList[]', $features,  (isset($video))?$video->features->pluck('id')->toArray():'', ['class' => 'select2 form-control', 'multiple' => true]) !!}
                    @if ($errors->has('features')) <p class="help-block">{{ $errors->first('features') }}</p> @endif
                </div>

              
                {!! Former::select('status','Status')->options([
                    null => 'Please Select',
                    0 => 'Active',
                    1 => 'Inactive',
                ]) !!}
                {!! Former::select('view_resource','Resources')->options([
                    null => 'Please Select',
                    0 => 'No',
                    1 => 'Yes',
                ]) !!}
                @if(!$edit)
                    {!! Former::submit('Create')->addClass('btn-primary') !!}
                @else
                    {!! Former::submit('Update')->addClass('btn-primary') !!}
                @endif
                {!! Former::close() !!}
            </div>
        </div>
        @if(!$edit)
    </create-edit-video>
@endif