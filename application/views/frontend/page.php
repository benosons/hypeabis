<!-- Section category title -->
<section class="block bg-lighter pb-0 pt-30 mt-40">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="block-title block-title-secondary heading-black m-b-0 m-t-20">
          <span class="title-angle-shap"> <?= $page[0]->page_title ?></span>
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
        <?php $content_file_path = base_url() . "assets/content/"; ?>
        <div class="texteditor_content">
          <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($page[0]->page_content)); ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- section end -->