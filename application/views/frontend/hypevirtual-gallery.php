<?php foreach ($contents as $key => $content) { ?> 
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="blog-post california">
                  <div class="row">
                    <div class="col-md-12 mx-auto">
                      <iframe src="<?= base_url().$content->virtual_galeri?>" height="600" width="1000" title="Iframe" frameBorder="0"></iframe>
                    </div>
                  </div>
                </div>
                <!-- end post-content -->
            </div>
        </div>
        <div class="row w-100 pt-2">
            <div class="col">
                <ul class="post-categories justify-content-center">
                </ul>
            </div>
        </div>

                    
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
