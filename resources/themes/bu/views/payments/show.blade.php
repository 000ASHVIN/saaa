@extends('app')

@section('content')
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form id="myCCForm" method="post">
				{!! csrf_field() !!}
				    <input id="token" name="token" type="hidden" value="" class="form-control">
				    <div>
				        <label>
				            <span>Card Number</span>
				        </label>
				        <input id="ccNo" type="text" size="20" value="" autocomplete="off" required class="form-control" />
				    </div>
				    <div>
				        <label>
				            <span>Expiration Date (MM/YYYY)</span>
				        </label>
				        <div class="row">
				        	<div class="col-md-6">
				        		<input type="text" size="2" id="expMonth" required class="form-control" />
				        	</div>
				        	<div class="col-md-6">
				        		<input type="text" size="2" id="expYear" required class="form-control" />
				        	</div>
				        </div>				      
				    </div>
				    <div>
				        <label>
				            <span>CVC</span>
				        </label>
				        <input id="cvv" size="4" type="text" value="" autocomplete="off" required class="form-control" />
				    </div>
				    <input type="submit" value="Submit Payment" class="btn btn-primary">
				</form>
			</div>
		</div>
	</div>
</section>
@stop

@section('scripts')
<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script>
    // Called when token created successfully.
    var successCallback = function(data) {
        var myForm = document.getElementById('myCCForm');

        // Set the token as the value for the token input
        myForm.token.value = data.response.token.token;

        // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
        myForm.submit();
    };

    // Called when token creation fails.
    var errorCallback = function(data) {
        if (data.errorCode === 200) {
            tokenRequest();
        } else {
            alert(data.errorMsg);
        }
    };

    var tokenRequest = function() {
        // Setup token request arguments
        var args = {
            sellerId: "901293591",
            publishableKey: "F553B882-4240-429D-9DDC-8F765C492823",
            ccNo: $("#ccNo").val(),
            cvv: $("#cvv").val(),
            expMonth: $("#expMonth").val(),
            expYear: $("#expYear").val()
        };

        // Make the token request
        TCO.requestToken(successCallback, errorCallback, args);
    };

    $(function() {
        // Pull in the public encryption key for our environment
        TCO.loadPubKey('sandbox');

        $("#myCCForm").submit(function(e) {
            // Call our token request function
            tokenRequest();

            // Prevent form from submitting
            return false;
        });
    });
</script>
@stop