<?php if (!$disable_search) { ?>
  <div class="contents-filter bg-light">
    <div class="container py-4">
      <?php echo form_open('page/' . $this->uri->segment(2), array('id' => 'filter-form', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label class="control-label" for="title">Judul</label>
              <input class="form-control no-dark" type="text" name="title" value="<?php echo $search_param['title'] ?>">
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label class="control-label" for="author">Penulis</label>
              <select id="author" name="author">
                <option value=""></option>
                <?php if (!empty($search_param['author'])) { ?>
                  <option value="<?php echo $search_param['author'] ?>" selected><?php echo $search_param['author_name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-12 col-lg-6">
            <div class="form-group">
              <label class="control-label" for="start-date">Tanggal</label>
              <div class="row">
                <div class="col-md-6" style="margin-bottom: 1rem;">
                 <div class="input-group tranparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">Mulai</span>
                    </div>
                    <input id="start-date" type="text" class="form-control datepicker-component" name="start_date" value="<?php echo $search_param['start_date']; ?>" style="background-color: #fff; color: #444" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group transparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">Akhir</span>
                    </div>
                    <input id="finish-date" type="text" class="form-control datepicker-component" name="finish_date" value="<?php echo $search_param['finish_date']; ?>" style="background-color: #fff; color: #444" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- row end -->

        <div class="row">
          <div class="col-12">
              <button class="btn btn-default"><i class="fa fa-search"></i> Filter</button>
              <button id="btn-reset" class="btn btn-default" type="reset"><i class="fa fa-times"></i> Reset</button>
          </div>
        </div>
      <?php echo form_close() ?>
    </div><!-- container end -->
  </div>
<?php } ?>

<!-- Section category article -->
<section class="content-section mt-5">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div id="photo-masonry" class="masonry">
          <?php foreach ($contents as $key => $content) { ?>
            <a href="<?php echo photo_url($content->id_content, $content->title) ?>" class="">
              <div class="grid">
                <div class="top_gallery_info_wrapper">
                  <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "="); ?>
                  <a class="<?php echo ($content->is_liked ? 'link-liked' : 'link-like') ?>" data-id="<?php echo $content->id_content ?>" title="<?php echo ($content->is_liked ? 'Liked' : 'Like') ?>" <?php echo ($this->session->userdata('user_logged_in') ? '' : 'data-redirect="' . base_url("page/login/{$redirect_url}") . '"') ?>>
                    <span class="like-counter align-middle" <?php echo ($content->like_count > 0 ? '' : 'style="display: none;"') ?>><?php echo number_format(ceil($content->like_count), 0, ',', '.'); ?></span>

                    <?php if ($content->is_liked) { ?>
                      <i class="fas fa-thumbs-up ml-1"></i>
                    <?php } else { ?>
                      <i class="far fa-thumbs-up ml-1"></i>
                    <?php } ?>
                  </a>

                  <?php if ($content->photo_counts > 1) { ?>
                    <i class="far fa-clone text-white ml-2" style="font-size: 15px;"></i>
                  <?php } ?>
                </div>

                <a href="<?php echo photo_url($content->id_content, $content->title) ?>">
                  <img data-src="<?php echo base_url() ?>assets/photo/thumb/<?php echo $content->photos[0]->picture_thumb ?>" style="margin-bottom: 0; min-height: 158px;">
                <a>
                <div class="grid__body">
                  <div class="gallery_info_wrapper">
                    <div class="d-flex">
                      <h1 class="grid__title text-white">
                        <?php echo strtoupper($content->title) ?>
                      </h1>
                    </div>
                    <div class="grid__author d-inline-block">
                      <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>">
                        <img data-src="<?php echo $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from); ?>" class="lazy rounded-circle" width="30" height="30" alt="Avatar">

                        <span class="text-white"><?php echo $content->user_name ?></span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          <?php } ?>
        </div>

        <?php if (!(isset($contents) && is_array($contents) && count($contents) > 0)): ?>
          <div class="row">
            <div class="col-md-12">
              <p>Data tidak ditemukan.</p>
            </div>
          </div>
        <?php else: ?>
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
$(document).ready(function () {
  $('#btn-reset').on('click', function () {
    $('[name=title]').val('')
  })

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
