<?php
if (!isset($edit)) $edit = false;
if (!isset($discounts)) $discounts = [];
?>
<div class="row">
    <div class="col-sm-12 col-md-12">
        @include('admin.store.listings.forms.create-edit',compact('edit'))
    </div>
</div>