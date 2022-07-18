<?php foreach ($contents as $key => $content) { ?> 
<section class="content-section">
    <div class="container">
        <input type="hidden" id="base_url_" value="<?= base_url() ?>">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="blog-post california">
                  <div class="row">
                    <div class="col-md-12 mx-auto">
                      <iframe id="iframe-virtual" val="<?= $content->id_galeri; ?>" src="<?= base_url().$content->virtual_galeri?>" height="600" width="1000" title="Iframe" frameBorder="0"></iframe>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
