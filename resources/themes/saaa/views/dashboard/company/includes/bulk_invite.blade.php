<div id="bulkInvite" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="/dashboard/company/bulkinvite" method="POST" enctype="multipart/form-data" style="margin-bottom: 0px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload List to Invite</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
              <p>
                Please download and complete the following file and add everyone that you wish to invite. Once completed, please upload the file here to invite all your staff members at once. <a href="https://www.dropbox.com/s/g627muf0m03vwa4/imports-template.xlsx?dl=1"><i class="fa fa-download"></i> Download Form</a>
              </p>
            </div>
            {!! csrf_field() !!}
            <input type="file" name="file" class="form-control">
        </div>
        <div class="modal-footer">
          <div class="button-group">
            <button type="submit" onclick="spin(this)" class="btn btn-success"><i class="fa fa-upload"></i>Upload & Send Invites</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>