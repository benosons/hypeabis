<?php $this->load->view('shared/polling/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
	<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">
					<?php if (strtolower($this->uri->segment(2)) == 'search'): ?>
						<h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
					<?php endif; ?>

					<?php echo $this->session->flashdata('message'); ?>

					<!-- Start Search Bar -->
					<?php #$this->load->view('shared/polling/search'); ?>
					<!-- End Search Bar -->

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr class="">
									<th class="text-nowrap" width="5%"><b>No</b></th>
									<th class="text-nowrap" width="100px"><b>Action</b></th>
									<th class="text-nowrap"><b>Judul</b></th>
									<th class="text-nowrap"><b>Tanggal submit</b></th>
									<th class="text-nowrap" width="175px"><b>Published</b></th>
									<th class="text-nowrap"><b><i class="fa fa-eye"></i></b></th>
									<th class="text-nowrap"><b><i class="fa fa-thumbs-up"></i></b></th>
									<th class="text-nowrap"><b><i class="fa fa-comment"></i></b></th>
									<th class="text-nowrap"><b><i class="fa fa-check-square-o"></i></b></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($polls as $index => $item): ?>
									<tr>
										<td class="text-nowrap"><?php echo ($offset + $index + 1); ?></td>
										<td class="text-nowrap">
											<a href="<?php echo $base_url ?>/view/<?php echo $item->id_content; ?>" title="View Content" class="btn btn-xs btn-info"><span class="fa fa-info-circle"></span></a>

                      <?php if (is_null($item->edit_id_admin) || $item->edit_id_admin == $this->session->userdata('id_admin')) { ?>
												<a href="<?php echo $base_url ?>/edit/<?php echo $item->id_content; ?>" title="Edit Content" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
												<?php if ($item->deletable == '1' || $this->session->userdata('admin_level') == '1') { ?>
													<a href="<?php echo $base_url ?>/delete/<?php echo $item->id_content; ?>" title="Delete Content" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete content?"><span class="fa fa-times"></span></a>
												<?php } ?>
											<?php } ?>

                      <?php if (!is_null($item->edit_id_admin)) { ?>
                        <?php if ($is_admin) { ?>
                          <?php if ($item->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                            <a href="<?php echo "{$base_url}/unlock_edit/{$item->id_content}" ?>" class="btn btn-xs btn-info" title="Unlock Editor"><span class="fa fa-unlock"></span></a>
                          <?php } elseif ($this->session->userdata('admin_level') == '1') { ?>
                            <a href="<?php echo "{$base_url}/unlock_edit/{$item->id_content}" ?>" class="btn btn-xs btn-complete" title="Sedang diedit oleh <?php echo $item->edit_admin_name ?>"><span class="fa fa-unlock"></span></a>
                          <?php } else { ?>
                            <?php $title = ($item->edit_id_admin !== $this->session->userdata('id_admin') ? "title='Sedang diedit oleh {$item->edit_admin_name}'" : '') ?>
                            <button class="btn btn-xs btn-danger" disabled <?php echo $title ?>><span class="fa fa-lock"></span></button>
                          <?php } ?>
                        <?php } else { ?>
                          <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-pencil"></span></button>
                          <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-times"></span></button>
                        <?php } ?>
                      <?php } ?>
										</td>
										<td>
											<b><?php echo $item->title; ?></b><br/>
											<?php echo base_url(); ?>poll/<?php echo $item->id_content . ($item->id_user ? '-' . strtolower(url_title($item->user_name)) : ''); ?>/<?php echo strtolower(url_title($item->title)); ?>/<?php echo strtolower(url_title($item->title)); ?>
										</td>
										<td><?php echo date('d-M-Y, H:i:s',strtotime($item->submit_date)); ?></td>
										<td>
											<?php $label_types = ['2' => 'success', '1' => 'info', '0' => 'warning', '-1' => 'default']?>
											<?php $text = ['2' => 'Terjadwal', '1' => 'Published', '0' => 'Menunggu approval', '-1' => 'Draft']?>

											<label class="label label-<?php echo $label_types[$item->content_status] ?>">
												<?php echo $text[$item->content_status] ?>
											</label>
										</td>
										<td><?php echo number_format(ceil($item->read_count), 0, ',', '.'); ?></td>
										<td><?php echo number_format(ceil($item->like_count), 0, ',', '.'); ?></td>
										<td><?php echo number_format($item->comment_count, 0, ',', '.'); ?></td>
										<td><?php echo number_format($item->vote_count, 0, ',', '.'); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<?php echo $this->pagination->create_links(); ?>
			</div>
			<!-- END CARD -->
		</div>
	</div>

	<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
