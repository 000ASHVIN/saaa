<div id="addUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="/dashboard/company/addusers" id="add_user_form" method="POST" enctype="multipart/form-data" style="margin-bottom: 0px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Users</h4>
        </div>
        <div class="modal-body">
            {!! csrf_field() !!}
            <label>Add the number of users you would like to add to your Practice Plan</labe>
            <input type="number" value="0" max="100" class="form-control" id="add_user" name="add_user">
        </div>
        <div class="modal-footer">
          <div class="button-group">
            <button type="button" onclick="submitform()" class="btn btn-success">Add</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>