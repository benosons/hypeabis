$(document).ready(function () {
    let base_url = $('#base_url').val();
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