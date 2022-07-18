<?php foreach ($contents as $key => $content) { ?> 
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="blog-post california">
                    <div class="row">
                        <div class="col">
                            <div class="post-content detail-content">
                                <center>
                                    <h3 class="post-title"><?= strtoupper($content->judul_galeri)?></h3>

                                    <div class="row justify-content-center">
                                        <div class="col-md-6 col-xl-5">
                                                                                            <div class="author-info-box">
                                                    <figure class="mb-0">
                                                        <a href="https://hypeabis.id/author/42/abdurachman">
                                                            <img src="https://hypeabis.id/assets/user/20210506125356000000abdurachman.jpg" alt="Image">
                                                        </a>
                                                    </figure>
                                                    <div class="content">
                                                        <a href="https://hypeabis.id/author/42/abdurachman">
                                                            <h5>Abdurachman</h5>
                                                        </a>
                                                        <small>Hypeabis.id</small>
                                                    </div>
                                                </div>
                                         </div>
                                    </div>

                                    <hr class="mt-20 mb-20">

                                          <div class="row align-items-center mb-4">
                                            <div class="col-sm-7">
                                                <div class="sharethis-inline-share-buttons text-sm-left st-center  st-inline-share-buttons st-animated" id="st-1"><div class="st-total st-hidden">
                                                  <span class="st-label"></span>
                                                  <span class="st-shares">
                                                    Shares
                                                  </span>
                                                </div><div class="st-btn st-first" data-network="facebook" style="display: inline-block;">
                                                  <img alt="facebook sharing button" src="https://platform-cdn.sharethis.com/img/facebook.svg">
                                                  
                                                </div><div class="st-btn" data-network="twitter" style="display: inline-block;">
                                                  <img alt="twitter sharing button" src="https://platform-cdn.sharethis.com/img/twitter.svg">
                                                  
                                                </div><div class="st-btn" data-network="whatsapp" style="display: inline-block;">
                                                  <img alt="whatsapp sharing button" src="https://platform-cdn.sharethis.com/img/whatsapp.svg">
                                                  
                                                </div><div class="st-btn" data-network="linkedin" style="display: inline-block;">
                                                  <img alt="linkedin sharing button" src="https://platform-cdn.sharethis.com/img/linkedin.svg">
                                                  
                                                </div><div class="st-btn st-last" data-network="telegram" style="display: inline-block;">
                                                  <img alt="telegram sharing button" src="https://platform-cdn.sharethis.com/img/telegram.svg">
                                                  
                                                </div></div>
                                                </div>
                                                <div class="col-sm-5 text-sm-right mt-3 mt-sm-0">
                                                  <i class="fa fa-thumbs-up"></i> Like</a>
                                                </div>
                                        </div>
                                      </center>

                                
                                <!-- end metas -->
                                                                                                                                    
<div class="row">
    <div class="col-md-8 mx-auto">
      <iframe src="<?= base_url().$content->virtual_galeri?>" height="600" width="800" title="Iframe"></iframe>
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
                <!-- end blog-post -->

                
                <!-- end author-info-box -->
                
                <!-- end post-navigation -->

            </div>
        </div>
    </div>
</section>
<?php } ?>
