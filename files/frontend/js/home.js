$(document).ready(function () {
    let base_url = $('#base_url').val();
    $('.link-like').on('click', function (event) {
        event.preventDefault();

        var $this = $(this)
        var post_data = {
            id_content: $(this).data('id')
        };
        var redirect = $this.data('redirect');

        if (redirect) {
            window.location = redirect;
            return;
        }

        $.ajax({
            'url': base_url + 'content2/likeContent',
            'type': 'POST', //the way you want to send data to your URL
            'data': getCSRFToken(post_data),
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                if (obj.status == 'success') {
                    Swal.fire('Terima kasih', 'telah menyukai hypephoto ini.', 'success');
                    $this.find('.like-counter').show().html(obj.like_count);
                    $this.off('click').removeClass('link-like').addClass('link-liked').attr('title', 'Liked');
                    $this.find('.far').removeClass('far').addClass('fas')
                } else if (obj.status == 'nologin') {
                    Swal.fire('Maaf', 'anda harus login terlebih dahulu untuk menyukai hypephoto ini.', 'error');
                } else if (obj.status == 'already_liked') {
                    Swal.fire('Terima kasih', 'anda sudah meyukai hypephoto ini.', 'warning');
                } else {
                    Swal.fire('Maaf', obj.message, 'error');
                }
                refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
            },
            'complete': function () {
            }
        });
    });
});