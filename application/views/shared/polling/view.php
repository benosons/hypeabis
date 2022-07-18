<?php $this->load->view('shared/polling/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
	<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">
					<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Tipe konten</label>
								<div class="col-md-10">
									<?php echo $content->paginated === '0' ? 'Standar' : 'Paginated' ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Tipe poll</label>
								<div class="col-md-10">
									<?php echo $content->type === '3' ? 'Classic' : 'Versus' ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Judul</label>
								<div class="col-md-10 col-xs-12">
									<?php echo $content->title ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Deskripsi singkat</label>
								<div class="col-md-10 col-xs-12">
									<?php echo $content->short_desc ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Meta title</label>
								<div class="col-md-10 col-xs-12">
									<?php echo $content->meta_title ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Meta description</label>
								<div class="col-md-10 col-xs-12">
									<?php echo $content->meta_desc ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Meta keyword</label>
								<div class="col-md-10 col-xs-12">
									<?php echo $content->meta_keyword ?>
								</div>
							</div>
						</div>

						<div id="file-pic-form-group" class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Gambar konten</label>
								<div class="col-md-10 col-xs-12">
									<?php if (!empty($content->content_pic)): ?>
										<div class="file-preview">
											<div class="file-preview-thumbnails">
												<div class="file-preview-frame">
													<img src="<?php echo base_url(); ?>assets/poll/<?php echo $content->content_pic ?>" class="file-preview-image" title="<?php echo $content->content_pic ?>" width="auto" style="max-height:100px">
												</div>
											</div>
											<div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Caption gambar</label>
								<div class="col-md-10 col-xs-12">
									<?php echo $content->pic_caption ?>
								</div>
							</div>
						</div>

						<div id="content-form-group" class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Konten</label>
								<div class="col-md-9 col-xs-12">
									<?php echo str_replace("##BASE_URL##", base_url() . "assets/content/", html_entity_decode($content->content)) ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Tags</label>
								<div class="col-md-10 col-xs-12">
									<?php echo implode(', ', array_column($tags, 'tag_name')) ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-2 control-label text-right sm-text-left">Status</label>
								<div class="col-md-10">
									<?php if ($content->content_status === '1'): ?>
										Published
									<?php elseif ($content->content_status === '0'): ?>
										Menunggu Approval
									<?php else: ?>
										Draft
									<?php endif; ?>
								</div>
							</div>
						</div>

						<?php if ($is_admin): ?>
							<div class="form-group">
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">Featured on homepage</label>
									<div class="col-md-10">
										<?php echo $content->featured_on_homepage === '1' ? 'Yes' : 'No' ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">Trending</label>
									<div class="col-md-10">
										<?php echo $content->trending === '1' ? 'Yes' : 'No' ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">Recommend on homepage</label>
									<div class="col-md-10">
										<?php echo $content->recommended === '1' ? 'Yes' : 'No' ?>
									</div>
								</div>
							</div>

							<div class="form-group" <?php echo ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>>
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">Deletable</label>
									<div class="col-md-10 col-xs-12">
										<?php echo $content->deletable === '1' ? 'Yes' : 'No' ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<?php if ($this->uri->segment(2) === 'edit'): ?>
						<?php $this->load->view('shared/polling/questions') ?>
					<?php endif; ?>
				</div>
			</div>

			<?php foreach ($questions as $question): ?>
				<div class="card card-transparent">
					<div class="card-body">
						<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
							<div class="row">
								<?php if ($question->picture): ?>
									<div class="col-md-3">
										<img src="<?php echo base_url() ?>assets/poll/questions/<?php echo $question->picture ?>" class="img img-fluid img-thumbnail" />
									</div>
								<?php endif; ?>
								<div class="col-md-9">
									<h4><?php echo $question->question ?></h4>
									<p>Total Vote: <?php echo array_sum(array_column($question->answers, 'counts')) ?></p>
								</div>
							</div>
							<div class="table-responsive m-t-20">
								<table class="table table-bordered table-striped border-top">
									<thead>
										<tr>
											<th>No</th>
											<th>Gambar</th>
											<th>Jawaban</th>
											<th class="text-right"><i class="fa fa-check-square-o"></i></th>
											<th class="text-right"><i class="fa fa-percent"></i></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($question->answers as $key => $answer): ?>
											<tr>
												<td><?php echo $key + 1 ?></td>
												<td>
													<?php if ($answer->picture): ?>
														<img src="<?php echo base_url() ?>assets/poll/answers/<?php echo $answer->picture ?>" class="img img-fluid img-thumbnail" style="max-width:150px;max-height:75px;" />
													<?php endif; ?>
												</td>
												<td><?php echo $answer->answer ?></td>
												<td class="text-right"><?php echo $answer->counts ?></td>
												<td class="text-right"><?php echo rtrim(rtrim(number_format($answer->percentage, 1, ',', '.'), '0'), ',') ?> %</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<!-- END CARD -->
		</div>
	</div>
</div>
