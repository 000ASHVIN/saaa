<div class="modal fade" id="login" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Login to my Account</h4>
            </div>

            <div class="modal-body">
                <div>
                    <login_web inline-template>

                        <div class="form-group">
                            <input type="text" name="email" value="{{ old('email') }}" v-model="forms.login.email" class="form-control" placeholder="Email Address">
                        </div>

                        <div class="form-group">
                            {!! Form::password('password', ['v-model' => 'forms.login.password', 'class' => 'form-control', 'placeholder' => 'Enter your password']) !!}
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button @click.prevent="sendLogin" class="btn btn-primary ladda-button"><i class="fa fa-unlock"></i> Login</button>
                            </div>
                        </div>
                    </login_web>
                </div>
            </div>
        </div>
    </div>
</div>
