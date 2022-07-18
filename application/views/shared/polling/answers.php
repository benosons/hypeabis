<div class="row p-t-40 m-b-40">
  <div class="col-md-8">
    <h4 id="answers" class="m-t-0 m-b-15 fw-600 text-heading-black">
      Daftar Pilihan Jawaban:
    </h4>
  </div>
  <div class="col-md-4">
    <!-- START card -->
    <div class="card card-transparent m-b-0">
      <?php if (!($content->type === '4' && count($question->answers) >= 2)): ?>
        <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
          <a href="<?php echo $base_url ?>/add_answer/<?php echo $question->id ?>" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Tambah jawaban">
            <i class="fa fa-plus"></i>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <!-- END card -->
  </div>
</div>

<?php echo $this->session->flashdata('answer_message'); ?>

<?php if (count($question->answers) > 0): ?>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr class="">
          <th class="text-nowrap" width="120px"><b>Action</b></th>
          <th class="text-nowrap" width="50px"><b>No</b></th>
          <th class="text-nowrap" width="200px">Gambar</th>
          <th class="text-nowrap"><b>Jawaban</b></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($question->answers as $item): ?>
        <tr>
          <td class="text-nowrap">
            <a href="<?php echo $base_url ?>/edit_answer/<?php echo $item->id ?>" title="Edit jawaban" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
            <a href="<?php echo $base_url ?>/delete_answer/<?php echo $item->id ?>" title="Delete jawaban" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete content?"><span class="fa fa-times"></span></a>
          </td>
          <td><?php echo $item->order_no ?></td>
          <td>
            <?php if ($item->picture): ?>
              <img src="<?php echo base_url() ?>assets/poll/answers/<?php echo $item->picture ?>" class="img img-fluid img-thumbnail" style="max-width:150px;max-height:75px;" />
            <?php endif; ?>
          </td>
          <td><?php echo $item->answer ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
<div class="alert alert-danger">Belum ada jawaban.</div>
<?php endif; ?>
