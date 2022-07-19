$(document).ready(function () {
    console.log(jQuery.fn.jquery);
    let theme = ''
    
    $('#darkSwitch').on('change', function(e){
        
        if($(theme).attr('data-theme') == 'dark'){
            $('#hype-visual-logo').addClass('title-about-light').removeClass('title-about-dark');
        }else{
            $('#hype-visual-logo').addClass('title-about-dark').removeClass('title-about-light');
        }
    })
    window.onload = function get_body() {
        theme = document.getElementsByTagName('body')[0];
        if($(theme).attr('data-theme') == 'dark'){
            $('#hype-visual-logo').addClass('title-about-dark').removeClass('title-about-light');
        }else{
            $('#hype-visual-logo').addClass('title-about-light').removeClass('title-about-dark');
        }
    }

    

});

$("iframe").on("load", function(){
    $(this).contents().on("mousedown, mouseup, click", function(){
    
            var post_data = {
                    id_galeri: $('#iframe-virtual').attr("val"),
                    type: 'click',
                    is_display: true,
                };
    
            $.ajax({
                'url' : $('#base_url_').val() + 'hypevirtual/clicked',
                'type' : 'POST', //the way you want to send data to your URL
                'data' : getCSRFToken(post_data),
                'complete' : function(){}
            });
    });
});
