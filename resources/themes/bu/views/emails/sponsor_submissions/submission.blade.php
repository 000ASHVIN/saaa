<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sponsor Page Form Submission</title>
</head>
<body>

<p style="font-size: 14px; font-family: Helvetica, Arial">Hi There!</p>
<p style="font-size: 14px; font-family: Helvetica, Arial">We have received a new form submission as per below: </p>
<p style="font-size: 14px; font-family: Helvetica, Arial">

    <strong>Name</strong>: {!! $submission['name'] !!}<br>
    <strong>Email</strong>: {!! $submission['email'] !!}<br>
    <strong>Contact Number</strong>: {!! $submission['contact_number'] !!}<br>
    <strong>Product Selected</strong>: {!! $submission['product'] !!}<br>
    <strong>Age</strong>: {!! $submission['age'] !!}<br>
    <strong>Type of Accountant</strong>: {!! $submission['accountant_type'] !!}<br>
    <strong>Income (R)</strong>: {!! $submission['income'] !!}<br>
    <strong>Gender</strong>: {!! $submission['gender'] !!}<br>
    <strong>Race</strong>: {!! $submission['race'] !!}<br>
    <strong>Level of Management</strong>: {!! $submission['level_of_management'] !!}<br>
    <hr>

    <p>Are you a member of a registered professional accountancy body? {!! ($submission['registered_professional_accountancy_body'] ? "Yes" : "No") !!}</p>
    <p>Professional Body Name: {!! $submission['professional_body_name'] !!}</p>
    @if($submission['professional_body_name'] == 'OTHER')
    <p>Other Professional Body Name: {!! $submission['other_professional_body_name'] !!}</p>
    @endif
    <p>Do you adhere to a Code of Conduct that is equal or similar to the IFAC Code of Conduct? {!! ($submission['do_you_adhere_to_a_code_of_conduct'] ? "Yes" : "No") !!}</p>
    <p>Are your CPD hours up to date as required by your professional body? {!! ($submission['are_your_cpd_hours_up_to_date'] ? "Yes" : "No") !!}</p>
    <p>Do you use engagement letters for all clients? {!! ($submission['do_you_use_engagement_letters'] ? "Yes" : "No") !!}</p>
    <p>Do you have access to the latest technical knowledge or library? {!! ($submission['latest_technical_knowledge_or_library'] ? "Yes" : "No") !!}</p>
    <p>Do you have the required infrastructure and resources to perform professional work for clients? {!! ($submission['do_you_have_the_required_infrastructure'] ? "Yes" : "No") !!}</p>
    <p>Do you or your firm perform reviews of all work performed by your professional support staff? {!! ($submission['do_you_or_your_firm_perform_reviews_of_all_work']? "Yes" : "No" ) !!}</p>
    <p>Do you apply relevant auditing and assurance standards when issuing reports on financial statements for clients? {!! ($submission['do_you_apply_relevant_auditing_and_assurance_standards'] ? "Yes" : "No")  !!}</p>
    <p>Do you use the latest technology and software to manage your practice and perform professional work? {!! ($submission['do_you_use_the_latest_technology_and_software'] ? "Yes" : "No") !!}</p>

</p>

<p style="font-size: 14px; font-family: Helvetica, Arial">
    Kind Regards,<br>
    SA Accounting Academy
</p>

</body>
</html>