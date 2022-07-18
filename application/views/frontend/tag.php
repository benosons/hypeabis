<!-- breadcrumb -->
<div class="breadcrumb-section bg-lighter">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <ol class="breadcrumb">
          <li>
            Konten terkait tag: <b>"<?= $tag; ?>"</b>
          </li>
        </ol>		
      </div>
    </div><!-- row end -->
  </div><!-- container end -->
</div>
<!-- breadcrumb end -->

<!-- Section category article -->
<section class="content-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <?php
          $views = [
            '1' => 'frontend/search/article',
            '3' => 'frontend/search/poll',
            '4' => 'frontend/search/poll',
            '5' => 'frontend/search/quiz',
            '6' => 'frontend/search/shoppable',
            '7' => 'frontend/search/photo',
          ]
        ?>
        <?php foreach ($contents as $content) { ?>
          <?php if (isset($views[$content->type])) { ?>
            <?php $this->load->view($views[$content->type], ['content' => $content]) ?>
          <?php } ?>
        <?php } ?>

        <?php if (!(isset($contents) && is_array($contents) && count($contents) > 0)) : ?>
          <div class="row">
            <div class="col-md-12">
              <p>Data tidak ditemukan.</p>
            </div>
          </div>
        <?php else : ?>
          <div class="row">
            <div class="col-md-12">
              <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- section end -->

<script>
$(document).ready(function() {
  $('#btn-reset').on('click', function() {
    $('[name=title]').val('')
    })
  })
  </script>

<script>
$(document).ready(function () {
  $('.link-like').on('click', function (event) {
    event.preventDefault();

    var $this = $(this)
    var post_data = {id_content: $(this).data('id')};
    var redirect = $this.data('redirect');

    if (redirect) {
      window.location = redirect;
      return;
    }

    $.ajax({
      'url' : '<?= base_url(); ?>' + 'content2/likeContent',
      'type' : 'POST', //the way you want to send data to your URL
      'data' : getCSRFToken(post_data),
      'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
        //if the request success..
        var obj = JSON.parse(data); // parse data from json to object..

        //if status not success, show message..
        if(obj.status == 'success'){
          Swal.fire('Terima kasih', 'telah menyukai hypephoto ini.', 'success');
          $this.find('.like-counter').show().html(obj.like_count);
          $this.off('click').removeClass('link-like').addClass('link-liked').attr('title', 'Liked');
          $this.find('.far').removeClass('far').addClass('fas')
        }
        else if(obj.status == 'nologin'){
          Swal.fire('Maaf', 'anda harus login terlebih dahulu untuk menyukai hypephoto ini.', 'error');
        }
        else if(obj.status == 'already_liked'){
          Swal.fire('Terima kasih', 'anda sudah meyukai hypephoto ini.', 'warning');
        }
        else{
          Swal.fire('Maaf', obj.message, 'error');
        }
        refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
      },
      'complete' : function(){}
    });
  });
})
</script>
