<a href="javascript:void(0);" class="btn btn-primary need-help-button" id="need_help_button" style="font-size: 14px;font-weight: 700;">
  Need Help ?
</a>

<?php
  $supportCategories = App\Blog\Category::select('id', 'title', 'slug', 'faq_type')
    ->where('parent_id', 0)
    ->where('faq_category_id','!=', '0')
    ->where('faq_type','=', 'general')
    ->orderBy('title', 'asc')
    ->get();

  $user = auth()->user();
?>
<support-ticket-popup :loggedin="{{ $user?1:0 }}" :categories="{{ $supportCategories }}" inline-template>
  <div id="support_ticket" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="support_ticket" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Need Help ?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 16px; right: 29px;"> 
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
          <div class="col-md-12">
                  
                  {!! Form::open(['method' => 'post', 'route' => 'support_tickets_popup.store', 'id'=>'support_ticket_popup_form', 'v-on:submit.prevent'=> 'checkForm()']) !!}
                  <div class="form-group" v-bind:class="{ 'has-error': errors.subject }">
                      {!! Form::label('subject', 'Subject') !!}
                      {!! Form::input('text', 'subject', null, ['class' => 'form-control', 'v-model' => 'forms.support_ticket.subject']) !!}
                      <p class="help-block" v-if="errors.subject" v-html="errors.subject"></p>
                  </div>

                  <div class="panel panel-default" v-if="createNew" style="margin:15px 0px;">
                    <div class="panel-body">
                        <p class="text-center">
                          We couldn't found any email record. Please create new account  <a href="/auth/register" target="_blank">Create an account </a>
                        </p>
                    </div>
                  </div>

                  <div class="form-group" v-if="!loggedin" v-bind:class="{ 'has-error': errors.support_email }">
                    {!! Form::label('support_email', 'Email Address') !!}
                    {!! Form::input('email', 'support_email', null, ['class' => 'form-control', 'v-model' => 'forms.support_ticket.support_email']) !!}
                    <p class="help-block" v-if="errors.support_email" v-html="errors.support_email"></p>
                  </div>

                  <div class="form-group" v-if="!loggedin" v-bind:class="{ 'has-error': errors.mobile }">
                      {!! Form::label('mobile', 'Mobile Number') !!}
                      {!! Form::input('text', 'mobile', null, ['class' => 'form-control', 'v-model' => 'forms.support_ticket.mobile', 'id'=>'support_ticket_mobile']) !!}
                      <p class="help-block" v-if="errors.mobile" v-html="errors.mobile"></p>
                  </div>

                  <?php
                    $category_type = [
                      'general' => 'General'
                    ];
                    
                    if($user) {
                      $category_type['technical'] = 'Technical';
                    }
                  ?>
                  <div class="form-group" v-if="0" v-bind:class="{ 'has-error': errors.type }">
                    {!! Form::label('type', 'Type') !!}
                    {!! Form::select('type', $category_type, $user?null:'general', 
                      ['class' => 'form-control', 
                        'placeholder'=>'Type', 
                        'v-model' => 'forms.support_ticket.type',
                        '@change' => 'typeChanged()'
                      ]) 
                    !!}
                    <p class="help-block" v-if="errors.type" v-html="errors.type"></p>
                  </div>

                  <div class="form-group" v-bind:class="{ 'has-error': errors.tag }">
                      {!! Form::label('tag', 'Ticket Category') !!}
                      <select name="tag" class="form-control" v-model="forms.support_ticket.tag" placeholder="Ticket Category">
                        <option :value="category.id" :selected="key==0" v-for="(key, category) in categoriesdd" v-html="category.title"></option>
                      </select>
                      <p class="help-block" v-if="errors.tag" v-html="errors.tag"></p>
                  </div>

                  <div class="form-group" v-bind:class="{ 'has-error': errors.description }">
                      {!! Form::label('description', 'Ticket Description') !!}
                      {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'support_ticket_summernote', 'v-model' => 'forms.support_ticket.description']) !!}
                      <p class="help-block" v-if="errors.description" v-html="errors.description"></p>
                  </div>

                  {!! Form::submit('Submit Ticket', ['class' => 'btn btn-primary']) !!}

                  @if ($user && $user->ViewResourceCenter())
                    <p style="margin-top:15px;">
                      <a href="{{ route('support_ticket.create') }}">Click here</a> to Ask a Technical Question
                    </p>
                  @endif

                  @if (!$user)
                    <p style="margin-top:15px;">
                      Ask a Technical Question is available to subscribers. <a href="{{ route('profession.plans_and_pricing') }}">Click here</a> to find out more
                    </p>
                  @endif
                  {!! Form::close() !!}
              </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</support-ticket-popup>