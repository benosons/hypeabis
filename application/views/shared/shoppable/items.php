<div id="items" class="row p-t-40 m-b-40" style="margin-top: -100px; padding-top: 140px !important;">
	<div class="col-md-8">
		<h4 class="m-t-10 m-b-15 fw-600 text-heading-black">
			Daftar Item:
		</h4>
	</div>
	<div class="col-md-4">
		<!-- START card -->
		<div class="card card-transparent m-b-0">
			<div class="card-body p-t-0 p-b-0 text-right sm-text-center">
			<?php if (($content->use_content_pic && $content->content_pic) || (!$content->use_content_pic && $content->shoppable_picture)): ?>
				<a href="<?php echo $base_url ?>/add_item/<?php echo $content->id_content ?>" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Tambah item">
					<i class="fa fa-plus"></i>
				</a>
			<?php endif; ?>
			</div>
		</div>
		<!-- END card -->
	</div>
</div>

<?php echo $this->session->flashdata('item_message'); ?>

<div class="row m-b-40">
	<div class="col-md-5">
		<div class="form-group">
			<label class="control-label">Gambar Shoppable</label>
			<?php if (($content->use_content_pic && $content->content_pic) || (!$content->use_content_pic && $content->shoppable_picture)): ?>
				<div class="shoppable-image-map">
					<img class="shoppable-image-image" src="<?php echo base_url(); ?>assets/shoppable/<?php echo $content->use_content_pic ? $content->content_pic : $content->shoppable_picture ?>" title="<?php echo $content->content_pic ?>" draggable="false">
					<?php foreach ($items as $item): ?>
						<div class="shoppable-image-pin" data-id="<?php echo $item->id ?>" style="top: <?php echo $item->top_percentage ?>%; left: <?php echo $item->left_percentage ?>%;">
							<div class="shoppable-image-pin-icon"><?php echo $item->order_no ?></div>
							<div class="shoppable-image-pin-body">
							</div>
							<div class="card no-border b-0 p-t-0">
								<?php if ($item->picture): ?>
									<img class="card-img-top" src="<?php echo base_url() ?>assets/shoppable/item/<?php echo $item->picture ?>" alt="Card image cap">
								<?php endif; ?>
								<div class="card-body text-center m-t-10">
									<h5 class="card-title"><?php echo $item->name ?></h5>
									<p class="card-subtitle">Rp. <?php echo number_format($item->price, 0, ',', '.') ?></p>
									<a href="<?php echo prep_url($item->url) ?>" class="btn btn-primary" target="_blank">Beli</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="text-center">
			<button id="go-to-shoppable-picture" class="btn btn-info m-b-10">Ubah Gambar Shoppable</button>
		</div>
	</div>
	<div class="col-md-7">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="">
							<th class="text-nowrap" width="120px"><b>Action</b></th>
							<th class="text-nowrap"><b>No</b></th>
							<th class="text-nowrap">Gambar</th>
							<th class="text-nowrap"><b>Item</b></th>
							<th class="text-nowrap"><b>Harga</b></th>
							<th class="text-nowrap"><b>Link</b></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items as $item): ?>
							<tr id="item-<?php echo $item->id ?>">
								<td class="text-nowrap">
									<a href="<?php echo $base_url ?>/edit_item/<?php echo $item->id ?>" title="Edit item" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
									<a href="<?php echo $base_url ?>/delete_item/<?php echo $item->id ?>" title="Delete item" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete content?"><span class="fa fa-times"></span></a>
								</td>
								<td><?php echo $item->order_no ?></td>
								<td>
									<?php if ($item->picture): ?>
										<img src="<?php echo base_url() ?>assets/shoppable/item/<?php echo $item->picture ?>" class="img img-fluid img-thumbnail" style="max-width:150px;max-height:75px;" />
									<?php endif; ?>
								</td>
								<td><?php echo $item->name ?></td>
								<td class="text-nowrap">Rp. <?php echo number_format($item->price, 0, ',', '.') ?></td>
								<td class="text-nowrap">
									<a href="<?php echo prep_url($item->url) ?>" class="btn btn-xs btn-info" target="_blank"><span class="fa fa-link"></span></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php if (($content->use_content_pic && !$content->content_pic) || (!$content->use_content_pic && !$content->shoppable_picture)): ?>
		<div class="alert alert-danger">Tambah Gambar Konten atau Gambar Shoppable terlebih dahulu</div>
		<?php elseif (count($items) < 1): ?>
		<div class="alert alert-danger">Belum ada item.</div>
		<?php endif; ?>
	</div>
</div>
