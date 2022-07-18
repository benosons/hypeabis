<?php foreach ($photos as $photo) { ?>
  <div class="row">
    <div class="col-md-8 mx-auto">
      <img data-src="<?php echo base_url() ?>assets/photo/<?php echo $photo->picture ?>" class="lazy img-fluid" alt="<?php echo $photo->short_desc ?>">
      <div class="mt-2 mb-3">
        <?= html_entity_decode($photo->short_desc); ?>
      </div>
    </div>
  </div>
<?php } ?>
