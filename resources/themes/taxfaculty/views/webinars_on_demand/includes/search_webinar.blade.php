<div class="search-form">
    {!! Form::hidden('type', '', ['id'=>'webinars_on_demand']) !!}
    {!! Form::hidden('browse', '', ['id'=>'browse_free_paid']) !!}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group @if ($errors->has('title')) has-error @endif">
                    {!! Form::label('title', 'Title') !!}
                    {!! Form::input('text', 'title', $request->title, ['class' => 'form-control event-title-filter','placeholder' => 'Webinar Name']) !!}
                    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                </div>
            </div>

            <div class=" col-md-3 form-group @if ($errors->has('webinar_complete')) has-error @endif" id="webinar_complete">
                {!! Form::label('webinar_complete', 'Completed CPD hours') !!}
                {!! Form::select('webinar_complete', ['N' => 'No', 'Y' => 'Yes'], $request->webinar_complete?$request->webinar_complete:'0', ['class' => 'form-control', 'placeholder' => 'All']) !!}
                
                @if ($errors->has('webinar_complete')) <p class="help-block">{{ $errors->first('webinar_complete') }}</p> @endif
            </div>
    
            <div class="col-md-3">
            <div class="form-group @if ($errors->has('category')) has-error @endif">
                {!! Form::label('category', 'Search by category') !!}
                <select name="category" id="category" class="form-control">
                    <option value="null">Please Select..</option>
                    <!-- <option value="null">All an “All”</option> -->
                    @foreach($webinar_categories as $key=>$cat)
                        <option {{ isset($category)? ($category == $cat->id) ? 'selected' : "" : "" }} value="{{ $cat->id }}" id="{{ $cat->slug }}">{{ $cat->title }}</option>
                    @endforeach 
                </select>
                @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
            </div>
            </div>
    
            <div class="col-md-3">
                <div class="form-group @if ($errors->has('sub_category')) has-error @endif" id="sub_cat">
                    {!! Form::label('sub_category', 'Search by Sub category') !!}
                    <select name="sub_category" id="sub_category" class="form-control">
                            <option value="">Select your sub category</option>
                    </select>
                    @if ($errors->has('sub_category')) <p class="help-block">{{ $errors->first('sub_category') }}</p> @endif
                </div>
            </div>
    
            <div class=" col-md-4 form-group @if ($errors->has('sub_sub_category')) has-error @endif" id="sub_sub_cat" style="display: none;">
                {!! Form::label('sub_sub_category', 'Search by Sub Sub category') !!}
                <select name="sub_sub_category" id="sub_sub_category" class="form-control">
                        <option value="">Select Sub Sub category</option>
                </select>
                @if ($errors->has('sub_sub_category')) <p class="help-block">{{ $errors->first('sub_sub_category') }}</p> @endif
            </div>
    
            <div class="col-md-3" style="margin-top: 25px">
                <button class="btn btn-primary search-button btn-block" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>
            </div>
        </div>
    </div>
    
    