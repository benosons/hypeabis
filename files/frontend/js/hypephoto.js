$(document).ready(function () {
    let base_url = $('#base_url').val();
    // $('body').on("click", '.link-like, .link-unlike', function(){
    //     $(this).toggleClass("link-like link-unlike");
    // });

    $('.link-like').on('click', function (event) {
        // event.preventDefault();

        var $this = $(this)
        var post_data = {id_content: $(this).data('id')};
        var redirect = $this.data('redirect');

        if (redirect) {
            window.location = redirect;
            return;
        }

        $.ajax({
            'url' : base_url + 'content2/likeContent',
            'type' : 'POST', //the way you want to send data to your URL
            'data' : getCSRFToken(post_data),
            'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                if(obj.status == 'success'){
                    Swal.fire('Terima kasih', obj.message, 'success');
                    $this.find('.like-counter').show().html(obj.like_count);
                    // $this.removeClass('link-like').addClass('link-unlike').attr('title', 'Unlike');
                    // $(this).toggleClass("link-like link-unlike").attr('title', 'Unlike');
                    // $this.find('.far').removeClass('far').addClass('fas');
                }
                else if(obj.status == 'nologin'){
                    Swal.fire('Maaf', 'anda harus login terlebih dahulu.', 'error');
                }
                else if(obj.status == 'already_liked'){
                    Swal.fire('Terima kasih', obj.message, 'warning');
                }
                else{
                    Swal.fire('Maaf', obj.message, 'error');
                }
                refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
            },
            'complete' : function(){}
        });
    });

    $('.link-unlike').on('click', function (event) {
        // event.preventDefault();
        console.log("unlike pressed..");

        var $this = $(this)
        var post_data = {id_content: $(this).data('id')};
        var redirect = $this.data('redirect');

        if (redirect) {
            window.location = redirect;
            return;
        }

        $.ajax({
            'url' : base_url + 'content2/unlikeContent',
            'type' : 'POST', //the way you want to send data to your URL
            'data' : getCSRFToken(post_data),
            'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                if(obj.status == 'success' || obj.status == 'already_unliked'){
                    Swal.fire('Terima kasih', 'Like anda telah dibatalkan.', 'success');
                    $this.find('.like-counter').show().html(obj.like_count);
                    // $this.removeClass('link-unlike').addClass('link-like').attr('title', 'Like');
                    // $(this).toggleClass("link-like link-unlike").attr('title', 'Like');
                    $this.find('.fas').removeClass('fas').addClass('far');
                }
                else if(obj.status == 'nologin') {
                    Swal.fire('Maaf', 'anda harus login terlebih dahulu untuk membatalkan like.', 'error');
                }
                else{
                    Swal.fire('Maaf', obj.message, 'error');
                }
                refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
            },
            'complete' : function(){}
        });
    });
});