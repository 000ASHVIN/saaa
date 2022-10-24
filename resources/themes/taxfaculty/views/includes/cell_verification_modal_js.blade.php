@if (Cookie::get('verify_cell') == 'yes')
<script>
    // $(document).ready(function() {
    //     setTimeout(function() {
    //         $('#cell_verification_popup_button').click();
    //     }, 2000);
    // });
</script>
@endif
<script>
    function getOtpForPopup() {
        var input = document.querySelector('#alert_cell_popup_field');
        var iti = window.intlTelInputGlobals.getInstance(input);
        var cell =iti.getNumber()
        if(cell==''){
            swal({
                title: "Warning!",
                text: "Enter the new number",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
            return false;
        }
        jQuery.ajax({
            method: 'get',
            url: '/dashboard/edit/cell_verification',
            data: {
                cell: cell
            },
            success: function(response) {
                if(response == true){
                    $('#change_cell_number').modal('hide');
                    $('#cell_verification_popup').modal('hide');
                    $('#cell_verification_form_popup_button').click();
                }
                if(response == false){
                    swal({
                        title: "invalid number",
                        text: "enter Proper number",
                        icon: "error",
                        buttons: true,
                        dangerMode: true,
                    });
                    return false;
                }               

            }
        });

    }
</script>