<div class="col-md-6">
    <div class="form-group " :class="{ 'has-error' : errors.invite.first_name.length>0}">
        {!! Form::label('first_name', 'First Name') !!}
        {!! Form::input('text', 'first_name', null, ['class' => 'form-control','v-model'=>'company.invite.first_name']) !!}
        <p v-if="errors.invite.first_name.length>0" class="help-block">@{{{ errors.invite.first_name[0] }}}</p>
    </div>
</div> 

<div class="col-md-6">
    <div class="form-group " :class="{ 'has-error' : errors.invite.last_name.length>0}">
        {!! Form::label('last_name', 'Last Name') !!}
        {!! Form::input('text', 'last_name', null, ['class' => 'form-control','v-model'=>'company.invite.last_name']) !!}
        <p v-if="errors.invite.last_name.length>0" class="help-block">@{{{ errors.invite.last_name[0] }}}</p>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group " :class="{ 'has-error' : errors.invite.cell.length>0}">
        {!! Form::label('cell', 'Cellphone Number') !!}
        {!! Form::input('text', 'cell', null, ['class' => 'form-control','v-model'=>'company.invite.cell']) !!}
        <p v-if="errors.invite.cell.length>0" class="help-block">@{{{ errors.invite.cell[0] }}}</p>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group " :class="{ 'has-error' : errors.invite.alternative_cell.length>0}">
        {!! Form::label('alternative_cell', 'Alternative Cellphone Number') !!}
        {!! Form::input('text', 'alternative_cell', null, ['class' => 'form-control','v-model'=>'company.invite.alternative_cell']) !!}
        <p v-if="errors.invite.alternative_cell.length>0" class="help-block">@{{{ errors.invite.alternative_cell[0] }}}</p>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group " :class="{ 'has-error' : errors.invite.email.length>0}">
        {!! Form::label('email', 'Email Address') !!}
        {!! Form::input('text', 'email', null, ['class' => 'form-control','v-model'=>'company.invite.email']) !!}
        <p v-if="errors.invite.email.length>0" class="help-block">@{{{ errors.invite.email[0] }}}</p>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group " :class="{ 'has-error' : errors.invite.id_number.length>0}">
        {!! Form::label('id_number', 'ID Number') !!}
        {!! Form::input('text', 'id_number', null, ['class' => 'form-control','v-model'=>'company.invite.id_number']) !!}
        <p v-if="errors.invite.id_number.length>0" class="help-block">@{{{ errors.invite.id_number[0] }}}</p>
    </div>
</div>
 
<div class="col-md-12">
    <hr>
    <a href="#" v-on:click.prevent="toggleStaff()" class="btn btn-primary"><i class="fa fa-close"></i> Cancel Invite</a>
    <button type="button" class="btn btn-success" v-on:click.prevent="sendInvitation()"     ><i class="fa fa-send"></i>{{ $button }}</button>
</div>