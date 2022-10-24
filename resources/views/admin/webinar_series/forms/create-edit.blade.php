<div class="panel panel-default">
    <div class="panel-heading">
        <div class="col-sm-12 col-md-8">
            <h4>Series Details</h4>
        </div>
    </div>
    <div class="panel-body">
        @if(!$edit)
            {!! Former::open()->route('admin.webinar_series.store') !!}
        @else
            {!! Former::open()->route('admin.webinar_series.update',$webinar_series->id) !!}
            {!! Former::populate($webinar_series) !!}
        @endif
        <div class="checkbox-wrapper">
            {!! Former::text('title','Name'); !!}
            <?= Former::checkboxes('fix_price_series', '')
            ->checkboxes([
                'Add fix price to series.' => [ 
                    'name' => 'fix_price_series',
                    'value' => '1'
                ]
            ]) ?>
        </div>
        {!! Former::text('originalAmount','Price')->setAttributes(['readonly' => true]); !!}
        {!! Former::text('discount','Discount %')->setAttributes(['placeholder' => '0']);!!}
        {!! Former::text('amount','Sales Price')->setAttributes(['readonly' => true]); !!}


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

        <div class="form-group @if ($errors->has('features')) has-error @endif">
            {!! Form::label('VideoFeaturesList', 'Please select your features') !!}
            {!! Form::select('VideoFeaturesList[]', $features,  (isset($webinar_series))?$webinar_series->features->pluck('id')->toArray():'', ['class' => 'select2 form-control', 'multiple' => true]) !!}
            @if ($errors->has('features')) <p class="help-block">{{ $errors->first('features') }}</p> @endif
        </div>

        {!! Former::textarea('description')->class('ckeditor')->rows(10)->autofocus() !!}

        @if(!$edit)
            {!! Former::submit('Create')->addClass('btn-primary') !!}
        @else
            {!! Former::submit('Update')->addClass('btn-primary') !!}
        @endif
        {!! Former::close() !!}
    </div>
</div>
