
<!-- Button trigger modal -->
<button style="display:none;" type="button" class="btn btn-primary" id="cell_verification_popup_button" data-toggle="modal" data-target="#cell_verification_popup" data-backdrop="static" data-keyboard="false">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="cell_verification_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Verify Cell Number</h5>
            </div>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="control-label">Cell Number</label>
                        <input name="cell" type="number" class="form-control" id="alert_cell_popup_field" value="{{ auth()->user()->cell ?? '' }}" style="padding-left: 0px !important;" >
                    </div>
                    <div class="margin-top10">
                        <button class="btn btn-primary" onclick="getOtpForPopup()"><i class="fa fa-check"></i> Get Code</button>
                    </div>

                    </div>
            </div>
        </div>
    </div>
</div>