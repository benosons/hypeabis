<?php $this->load->view('admin/sponsored/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper"> <!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">
					<?php echo $this->session->flashdata('message'); ?>

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr class="">
									<th class="text-nowrap" width="5%"><b>No</b></th>
									<th class="text-nowrap" width="100px"><b>Action</b></th>
									<th class="text-nowrap"><b>Judul</b></th>
									<th class="text-nowrap"><b>Posisi</b></th>
									<th class="text-nowrap"><b>Tanggal Tayang</b></th>
									<th class="text-nowrap"><b>Tanggal update</b></th>
									<th class="text-nowrap"><b>Published</b></th>
									<th class="text-nowrap"><b>Shown</b></th>
									<th class="text-nowrap"><b>Clicked</b></th>
									<th class="text-nowrap"><b>CTR</b></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($contents as $key => $content): ?>
									<tr>
										<td class="text-nowrap"><?php echo $key + $offset + 1 ?></td>
										<td class="text-nowrap">
                      <?php if (is_null($content->edit_id_admin) || $content->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                        <a class="btn btn-xs btn-info" href="<?php echo base_url() ?>admin_sponsored/edit/<?php echo $content->id_content ?>" title="Edit">
                          <span class="fa fa-pencil"></span>
                        </a>
                        <a class="btn btn-xs btn-info btn-need-confirmation" href="<?php echo base_url() ?>admin_sponsored/delete/<?php echo $content->id_content ?>" title="Delete" data-message="Are you sure want to delete this?">
                          <span class="fa fa-times"></span>
                        </a>
                      <?php } ?>

                      <?php if (!is_null($content->edit_id_admin)) { ?>
                        <?php if ($content->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                          <a href="<?php echo base_url("admin_sponsored/unlock_edit/{$content->id_content}") ?>" class="btn btn-xs btn-info" title="Unlock Editor"><span class="fa fa-unlock"></span></a>
                          <?php } elseif ($this->session->userdata('admin_level') == '1') { ?>
                            <a href="<?php echo base_url("admin_sponsored/unlock_edit/{$content->id_content}") ?>" class="btn btn-xs btn-complete" title="Sedang diedit oleh <?php echo $content->edit_admin_name ?>"><span class="fa fa-unlock"></span></a>
                        <?php } else { ?>
                          <?php $title = ($content->edit_id_admin !== $this->session->userdata('id_admin') ? "title='Sedang diedit oleh {$content->edit_admin_name}'" : '') ?>
                          <button class="btn btn-xs btn-danger" disabled <?php echo $title ?>><span class="fa fa-lock"></span></button>
                        <?php } ?>
                      <?php } ?>
										</td>
										<td><?php echo $content->title ?></td>
										<td><?php echo $content->position_name ?></td>
										<td>
											<span class="text-nowrap"><?php echo date('d M Y', strtotime($content->start_date)) ?></span> - <span class="text-nowrap"><?php echo date('d M Y', strtotime($content->finish_date)) ?></span>
										</td>
                    <td><?= date('d-M-Y, H:i:s', strtotime($content->submit_date)); ?></td>
										<td>
											<?php if ($content->content_status === '-1'): ?>
												<label class="label label-default">Draft</label>
											<?php else: ?>
												<label class="label label-danger">Published</label>
											<?php endif; ?>
										</td>
										<td><?php echo $content->view_count ?></td>
										<td><?php echo $content->click_count ?></td>
										<td class="text-nowrap">
											<?php if ($content->view_count > 0): ?>
												<?php echo rtrim(rtrim(number_format(($content->click_count / $content->view_count) * 100, 2, ',', '.'), '0'), ','); ?> %
											<?php else: ?>
												0 %
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<?php if (count($contents) === 0): ?>
						<p class="m-t-40">Belum ada data artikel - sponsored.</p>
					<?php endif; ?>

					<?php echo $this->pagination->create_links() ?>
				</div>
			</div>
			<!-- END CARD -->
		</div>
	</div>

	<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
