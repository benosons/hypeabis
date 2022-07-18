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
        <div id="shop-masonry" class="masonry">
          <?php foreach ($contents as $key => $content) { ?>
            <a href="<?php echo base_url() . 'hypeshop/' . $content->id_content . '/' . strtolower(url_title($content->title)) ?>">
              <div class="grid">
                <img data-src="<?php echo $assets_url ?>/thumb/<?php echo $content->content_pic_thumb ?>">
                <div class="grid__body">
                  <div class="gallery_info_wrapper">
                    <div class="d-flex">
                      <h1 class="grid__title">
                        <?php echo strtoupper($content->title) ?>
                      </h1>
                    </div>
                  </div>
                </div>
                <div class="overlay"></div>
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
})
</script>
