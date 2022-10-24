<script>
    function spin(this1)
    {
        this1.closest("form").submit();
        this1.disabled=true;
        this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
    }

    function send(this1)
    {
        this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Sending..`;
        this1.click();
    }

    $('#custom_date').on('click', function () {
        $('#date_range').datepicker();
    })

    $('#SelectAll').click(function(){
        $(':checkbox').prop("checked", true);
    });

    $('#deselectAll').click(function(){
        $(':checkbox').prop("checked", false);
    });

</script>