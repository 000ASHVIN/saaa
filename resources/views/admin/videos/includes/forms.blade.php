<?php
if (!isset($edit)) $edit = false;
if (!isset($recordings)) $recordings = [];
?>
<div class="row">
    <div class="col-sm-12 col-md-6">
        @include('admin.videos.forms.create-edit',compact('edit'))
    </div>
    <div class="col-sm-12 col-md-6">
        @include('admin.videos.forms.recordings',compact('edit','video','recordings'))
    </div>
</div>