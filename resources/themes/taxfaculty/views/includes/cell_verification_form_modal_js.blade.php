@if (Cookie::get('verify_cell_form') == 'yes')
<script>
    $('#cell_verification_form_popup_button').click();
</script>
@endif
<script>
    $().ready(function() {
        
        var input = document.querySelector("#alert_cell_popup_field");
        window.intlTelInput(input, {
            // allowDropdown: false,
            autoHideDialCode: false,
            autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            initialCountry: "za",
            formatOnDisplay: true,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            hiddenInput: "full_number_popup",
            // initialCountry: "auto",
            // localizedCountries: { 'de': 'Deutschland' },
            nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "/assets/frontend/js/utils.js",
        });
    });

    function verifyOtp() {
        if (document.getElementById('code_for_otp').value == '') {
            swal({
                title: "Warning!",
                text: "Enter the otp",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
            return false;
        }
        jQuery.ajax({
            method: 'post',
            url: '/dashboard/edit/cell_verification',
            data: {
                _token: document.getElementById("token_for_otp").value,
                code: document.getElementById("code_for_otp").value
            },
            success: function(response) {
                if (response) {
                    swal({
                        title: "verified!",
                        text: "Your Cell is verified",
                        icon: "success",
                        buttons: true,
                        dangerMode: true,
                    });
                    setTimeout(function() {
                        window.location.replace(window.location.href);
                    }, 1000);
                } else {
                    swal({
                        title: "Wrong otp",
                        text: "Please Enter Valid OTP Code",
                        icon: "error",
                        buttons: true,
                    });
                }

            }
        });

    }
</script>