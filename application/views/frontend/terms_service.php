<!-- Section category title -->
<section class="block bg-lighter p-b-0 p-t-30">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="block-title block-title-secondary heading-black m-b-0 m-t-20">
          <span class="title-angle-shap"> Ketentuan Layanan</span>
        </h2>
      </div>
    </div><!-- row end -->
  </div>
</section>
<!-- section end -->

<!-- Section category article -->
<section class="block">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <? $content_file_path = base_url() . "assets/content/"; ?>
        <div class="texteditor_content">
          <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($global[0]->term_condition)); ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- section end -->