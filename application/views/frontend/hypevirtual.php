<br />
<!-- Main Slider -->
<div class="container">
<section class="content-section no-spacing">
    <div id="slider-home" class="carousel slide" data-ride="carousel" data-pause="false">
        <ul class="carousel-indicators">
		<?php foreach ($gallery as $xx => $carousel){ 
			if($xx <= 3){
		?>
            <li data-target="#slider-home" data-slide-to="<?= $xx ?>" class="<?= $result = $xx == 0 ? 'active' : ''; ?>"></li>
		<?php 
			}
		} 
		?>
        </ul>
        <div class="carousel-inner">
			<?php foreach ($gallery as $xx => $carousel){ 
				if($xx <= 3){
				?>
				<div id="#slider-<?= $carousel->id_galeri ?>" class="carousel-item <?= $result = $xx == 0 ? 'active' : ''; ?>">
					<img alt="<?= strtoupper($carousel->judul_galeri); ?>" src="<?= base_url('assets/content/').$carousel->thumb_galeri ?>">
					<!-- <div class="carousel-caption">
						<h3>discover a new you!</h3>
						<p>Transform your look today with our special offers!</p>
						<a class="btn" href="#">Make an appointment</a>
					</div> -->
				</div>
			<?php 
				}
			} 
			?>
        </div>
        <a class="carousel-control-prev" href="#slider-home" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#slider-home" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>
</section>
<!-- end Main Slider -->
<br />
<!-- Adv -->
<?php
if ((isset($ads['sup_leaderboard_hm']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_hm']['builtin'][0]['id_ads'] > 0) ||
    (isset($ads['sup_leaderboard_hm']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_hm']['googleads'][0]['id_ads'] > 0)
) {
    ?>
    
<?php } ?>
<!-- end Adv -->
<section class="content-section">
    <div class="row">
        <div class="col-sm-4">
            <div class="title-about-light" id="hype-visual-logo">
                <p>HYPE</p>
                <p>VIRTUAL</p>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="row">
            <?php foreach ($gallery as $xx => $carousel){ 
				if($xx <= 3){
				?>
				<div class="col-sm-4">
                    <div class="text"><?= $carousel->deskripsi ?></div>
                </div>
			<?php 
				}
			} 
			?>
            
            </div>
        </div>
    
    </div>
</section>
<!-- Gres (Terbaru) -->
<section class="content-section">
        <!-- <div class="row">
            <div class="col-12">
                <center>
                    <h2 class="section-title">Gres</h2>
                </center>
            </div>
        </div> -->
        <div class="row">
        <?php foreach ($gallery as $x => $gallery){ ?>
            <div class="gallery col-sm-4">
                <a href="<?php echo gallery_url($gallery->id_galeri, 0,  $gallery->judul_galeri) ?>" >
                <img alt="<?= strtoupper($gallery->judul_galeri); ?>" src="<?= base_url('assets/content/').$gallery->thumb_galeri ?>">
                </a>
                <div class="desc"><?= strtoupper($gallery->judul_galeri); ?></div>
            </div>
        <?php } ?>

            <!-- <div class="gallery col-sm-4">
                <a href="http://xomisse.com" target="_blank">
                <img alt="SEO-IMAGE-CAPTION" src="https://3.bp.blogspot.com/-CdGsUaeD7V8/WcKK3_7ocvI/AAAAAAAADEQ/XuB8CLfBnfkHKB4Z8-2er7Jx6ecPV3G9ACKgBGAs/s1600/DeathtoStock_Park2.jpg">
                </a>
                <div class="desc">DISPLAYED-CAPTION</div>
            </div>

            <div class="gallery col-sm-4">
                <a href="http://xomisse.com" target="_blank">
                <img alt="SEO-IMAGE-CAPTION" src="https://3.bp.blogspot.com/-jhrcjaaL3As/WcKKuLI2IDI/AAAAAAAADEM/2A-uu5a5TR4Qz8ywaHOpoYW93ddPSlWQwCKgBGAs/s1600/SimoneAnne-1862.jpg">
                </a>
                <div class="desc">DISPLAYED-CAPTION</div>
            </div>

            <div class="gallery col-sm-4">
                <a href="http://xomisse.com" target="_blank">
                <img alt="SEO-IMAGE-CAPTION" src="https://1.bp.blogspot.com/-5vYnmxTeZOc/WcKK38jeMeI/AAAAAAAADEQ/6UUcki4fVqw7GsKC_PfxZ24ZvDRNdtKKACKgBGAs/s1600/DeathtoStock_Food5.jpg">
                </a>
                <div class="desc">DISPLAYED-CAPTION</div>
            </div>

            <div class="gallery col-sm-4">
                <a href="http://xomisse.com" target="_blank">
                <img alt="SEO-IMAGE-CAPTION" src="https://1.bp.blogspot.com/-5vYnmxTeZOc/WcKK38jeMeI/AAAAAAAADEQ/6UUcki4fVqw7GsKC_PfxZ24ZvDRNdtKKACKgBGAs/s1600/DeathtoStock_Food5.jpg">
                </a>
                <div class="desc">DISPLAYED-CAPTION</div>
            </div>

            <div class="gallery col-sm-4">
                <a href="http://xomisse.com" target="_blank">
                <img alt="SEO-IMAGE-CAPTION" src="https://3.bp.blogspot.com/-jhrcjaaL3As/WcKKuLI2IDI/AAAAAAAADEM/2A-uu5a5TR4Qz8ywaHOpoYW93ddPSlWQwCKgBGAs/s1600/SimoneAnne-1862.jpg">
                </a>
                <div class="desc">DISPLAYED-CAPTION</div>
            </div> -->

        </div>
    </div>
    <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- end Gres (Terbaru) -->
</div>
<hr>