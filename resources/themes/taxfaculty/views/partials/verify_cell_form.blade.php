<!-- Button trigger modal -->
<button style="display:none;" type="button" class="btn btn-primary" id="cell_verification_form_popup_button" data-toggle="modal" data-target="#cell_verification_form_popup" data-backdrop="static" data-keyboard="false">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="cell_verification_form_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Verify Cell Number</h5>
            </div>
            <div class="modal-body">
                
                    
            <div>
                <input type="hidden" id="token_for_otp" name="_token" value="<?php echo csrf_token(); ?>">
                <div id="divOuter">
                <div class="form-group" id="divInner">
                    <label class="control-label">Enter Otp here</label>
                    <!-- <input name="code" id="code_for_otp" type="text" class="form-control"> -->
                    <input name="code" id="code_for_otp" type="text" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  onKeyPress="if(this.value.length==4) return false;" />
                </div>
                </div>
                <div class="margin-top10">
                    <button class="btn btn-primary" onclick="verifyOtp()"><i class="fa fa-check"></i> Verify</button>
                </div>

            </div>
            </div>
        </div>
    </div>
</div>