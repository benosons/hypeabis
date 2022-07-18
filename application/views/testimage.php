<?= $this->session->flashdata('message'); ?>

<?= form_open_multipart("Testimage/saveAdd"); ?>
<input type="file" class="file" name="file_pic" id="file_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
<button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
<?= form_close(); ?>