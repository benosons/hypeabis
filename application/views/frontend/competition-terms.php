<?php $this->load->view('frontend/competition-header') ?>

<!-- Hypephoto -->
<section class="content-section pt-0">
    <div class="container-fluid justify-content-center">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-3 mb-3">
                <?php $content_file_path = base_url() . "assets/content/"; ?>
                <?php echo str_replace("##BASE_URL##", $content_file_path, html_entity_decode($competition->description)); ?>
            </div>
        </div>
        <div class="mt-3">
            <?php echo $this->pagination->create_links(); ?>
        </div>
    </div>
</section>
