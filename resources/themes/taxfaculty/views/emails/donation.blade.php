<p>Hello,</p>
<p>Please find recent donation detail as follow:</p>
<p>First Name: {{ $donation->first_name }}</p>
<p>Last Name: {{ $donation->last_name }}</p>
<p>Company Name: {{ $donation->company_name }}</p>
<p>Email: {{ $donation->email }}</p>
<p>Cell: {{ $donation->cell }}</p>
<p>Address: {{ $donation->address }}</p>
<p>Amount: {{ $donation->amount }}</p>
<p>Payment Method: {{ $donation->paymentOption }}</p>
<p>Payment Status: {{ $donation->status?'Success':'Payment Fail' }}</p>
@if (!$donation->status)
    <p>Payment Reason: {{ $donation->failure_reason }}</p>
@endif