<div class="contents-filter bg-light">
  <div class="container py-4">
    <?php echo form_open('search/' . $this->uri->segment(2), array('id' => 'filter-form', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label class="control-label" for="title">Judul</label>
          <input class="form-control no-dark" type="text" name="title" value="<?php echo $search_param['title'] ?>">
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label class="control-label" for="category">Kanal</label>
          <select id="category" class="w-100" name="category">
            <option value=""></option>
            <?php $categories_count = count($categories) ?>
            <?php foreach ($categories as $key => $item) { ?>
              <option value="<?php echo $item['id_category']; ?>" <?php echo ($search_param['category'] == $item['id_category'] ? "selected" : ""); ?>><?php echo $item['category_name']; ?></option>

              <?php if ($key == $categories_count - 2) { ?>
                <option value="hypeshop" <?php echo ($search_param['hypeshop'] ? "selected" : ""); ?>>Hypeshop</option>
                <option value="hypephoto" <?php echo ($search_param['hypephoto'] ? "selected" : ""); ?>>Hypephoto</option>
              <?php } ?>
            <?php } ?>
          </select>
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
      <div class="col-md-12 col-lg-5">
        <div class="form-group">
          <label class="control-label" for="start-date">Tanggal</label>
          <div class="row">
            <div class="col-md-6" style="margin-bottom: 1rem;">
              <div class="input-group tranparent">
                <div class="input-group-prepend">
                  <span class="input-group-text transparent">Mulai</span>
                </div>
                <input id="start-date" type="text" class="form-control datepicker-range-start" name="start_date" value="<?php echo $search_param['start_date']; ?>" style="background-color: #fff; color: #444" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group transparent">
                <div class="input-group-prepend">
                  <span class="input-group-text transparent">Akhir</span>
                </div>
                <input id="finish-date" type="text" class="form-control datepicker-range-finish" name="finish_date" value="<?php echo $search_param['finish_date']; ?>" style="background-color: #fff; color: #444" />
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

<!-- Section category article -->
<section class="content-section mt-5">
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
