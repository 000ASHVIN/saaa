<h4>Newsletter Subscription</h4>
<p>Subscribe to our newsletter to receive updates as soon as new products has been loaded.</p>

<form class="validate" action="/newsletter/subscribe" method="post"
      data-success="Subscribed! Thank you!" data-toastr-position="bottom-right">
    {!! csrf_field() !!}

    <div style="display: flex">
        <input type="text" class="form-control required" name="first_name" style="margin: 2px 2px 5px;" placeholder="First Name">
        <input type="text" class="form-control" name="last_name" style="margin: 2px 2px 5px;" placeholder="Last Name">
    </div>

    <div style="display: flex">
        <input type="email" name="email" class="form-control required" placeholder="Enter your Email" style="margin: 2px 2px 5px;">
    </div>

    <hr>

    <div class="input-group-addon" style="border: none; padding: 2px; color: white; background-color: #800000; border-color: #800000;">
        <button class="btn btn-success btn" style="border: none; padding: 2px; color: white; background-color: #800000; border-color: #800000;" type="submit"> <i class="fa fa-envelope"></i> Subscribe to newsletter </button>
    </div>
</form>