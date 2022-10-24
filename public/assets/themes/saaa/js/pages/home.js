function spin(this1)
{
    this1.closest("form").submit();
    this1.disabled=true;
    this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
}
$(document).ready(function(){
    
    // setTimeout(function(){ $('.custom_filter').first().click();}, 3000);

    var filter = $("ul.mix-filter li:first").data('filter');
    if(filter != null){
        $('.filter_result').hide();
        $('.filter_result.' + filter).show();
    }

    $('.custom_filter').on('click', function(){
        $('.custom_filter').removeClass('active');
        if(!$(this).hasClass('active')) {
            var filter = $(this).data('filter');
            $('.filter_result').hide();
            $('.filter_result.' + filter).show();
        }
    })
    
    function setLiHeight() {
        if($(window).width() > 768) {
            var height = 0;
            $('ul.clients-dotted>li').each(function() {
                var liHeight = $(this).height();
                if(liHeight > height) {
                    height = liHeight;
                }
            })
            $('ul.clients-dotted>li').height(height);
        } else {
            $('ul.clients-dotted>li').css('height', 'unset');
        }
    }
    setLiHeight();
    $(window).resize(() => {
        setLiHeight();
    })
});
