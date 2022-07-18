<?php $this->load->view('shared/quiz/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
	<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">

          <div class="d-flex">
						<h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
							<?php echo $heading_text ?> Quiz
						</h4>
            <div>
              <?php if (isset($content) && $is_admin) { ?>
                <?php if ($content->edit_id_admin) { ?>
                  <a class="btn btn-complete <?php echo ((!is_null($locked_content_id) && $content->id_content !== $locked_content_id) ? 'disabled' : '') ?>" href="<?php echo $base_url ?>/unlock_edit/<?php echo $content->id_content ?>">
                    <i class="fa fa-unlock mr-1"></i> Unlock Editor
                  </a>
                <?php } else { ?>
                  <a class="btn btn-danger <?php echo ((!is_null($locked_content_id) && $content->id_content !== $locked_content_id) ? 'disabled' : '') ?>" href="<?php echo $base_url ?>/lock_edit/<?php echo $content->id_content ?>" style="height:">
                    <i class="fa fa-lock mr-1"></i> Lock Editor
                  </a>
                <?php } ?>

                <?php if (!is_null($locked_content_id) && $content->id_content !== $locked_content_id) { ?>
                  <a class="btn btn-complete" href="<?php echo $base_url ?>/edit/<?php echo $locked_content_id ?>">
                    <i class="fa fa-pencil mr-1"></i> Edit Quiz Terkunci
                  </a>
                <?php } ?>
              <?php } ?>
            </div>
					</div>

					<?php echo $this->session->flashdata('message') ?>

					<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
						<?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off']); ?>
							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Tipe konten</label>
									<div class="col-md-8">
										<div class="radio radio-complete">
											<input type="radio" value="0" name="paginated" id="paginated0" <?php echo ($form_value['paginated'] == 0 ? 'checked="checked"' : ''); ?>>
											<label for="paginated0">Standar</label>
											<input type="radio" value="1" name="paginated" id="paginated1" <?php echo ($form_value['paginated'] == 1 ? 'checked="checked"' : ''); ?>>
											<label for="paginated1">Paginated</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Judul</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" name="title" value="<?php echo $form_value['title'] ?>" required/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Deskripsi singkat</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" name="short_desc" value="<?php echo $form_value['short_desc'] ?>"/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Meta title</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" name="meta_title" value="<?php echo $form_value['meta_title'] ?>"/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Meta description</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" name="meta_desc" value="<?php echo $form_value['meta_desc'] ?>"/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Meta keyword</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" name="meta_keyword" value="<?php echo $form_value['meta_keyword'] ?>"/>
									</div>
								</div>
							</div>

							<div id="file-pic-form-group" class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Gambar konten</label>
									<div class="col-md-8 col-xs-12">
										<?php if (!empty($content->content_pic)): ?>
											<div class="file-preview">
												<?php if ($content->content_status !== '1'): ?>
													<a href="<?php echo $base_url ?>/delete_picture/<?php echo $content->id_content ?>" class="close fileinput-remove text-right" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
												<?php endif; ?>
												<div class="file-preview-thumbnails">
													<div class="file-preview-frame">
														<img src="<?php echo base_url(); ?>assets/quiz/<?php echo $content->content_pic ?>" class="file-preview-image" title="<?php echo $content->content_pic ?>" width="auto" style="max-height:100px">
													</div>
												</div>
												<div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
											</div>
										<?php endif; ?>
										<input type="file" class="file" name="file_pic" id="file_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
										<p class="hint-text"><small>*(Maksimal 5MB. Rekomendasi ukuran: <?php echo $this->pic_width; ?>px x <?php echo $this->pic_height; ?>px. Format file yang diperbolehkan: jpg, png &amp; gif.)</small></p>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Caption gambar</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" name="pic_caption" value="<?php echo $form_value['pic_caption'] ?>"/>
										<p class="hint-text">
											<small>*Caption gambar wajib diisi dengan deskripsi gambar dan sumber gambar. Contoh: Berinvestasi sejak dini akan bermanfaat bagi masa depan (Sumber gambar: ABCD)</small>
										</p>
									</div>
								</div>
							</div>

							<div id="content-form-group" class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Konten</label>
									<div class="col-md-9 col-xs-12">
										<textarea id="content" class="form-control" name="content" rows="15" style="height:300px;" required><?php echo str_replace("##BASE_URL##", base_url() . "assets/content/", html_entity_decode($form_value['content'])) ?></textarea>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Tags</label>
									<div class="col-md-8 col-xs-12">
										<input type="text" class="tagsinput form-control" name="tags" value="<?php echo isset($form_value['tags']) ? $form_value['tags'] : '' ?>"/>
										<p class="hint-text"><small>*Ketikkan label/tag konten lalu tekan enter untuk menambahkan</small></p>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Status</label>
									<div class="col-md-8">
										<div class="radio radio-complete">
                      <input type="radio" value="-1" name="content_status" id="content_status-1" <?php echo ($form_value['content_status'] === '-1' || (!$is_admin && $form_value['content_status'] !== '0') ? 'checked="checked"' : ''); ?>>
                      <label for="content_status-1">Simpan sebagai draft</label>

                      <input type="radio" value="0" name="content_status" id="content_status0" <?php echo ($form_value['content_status'] === '0' ? 'checked="checked"' : ''); ?>>
                      <label for="content_status0"><?php echo ($is_admin ? 'Waiting approval' : 'Kirim Quiz') ?></label>

                      <?php if ($is_admin) { ?>
                        <input type="radio" value="1" name="content_status" id="content_status1" <?php echo ($form_value['content_status'] === '1' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status1">Publish</label>

                        <input type="radio" value="2" name="content_status" id="content_status2" <?php echo ($form_value['content_status'] === '2' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status2" class="mb-0">Publish Terjadwal</label>
                      <?php } ?>
										</div>
									</div>
								</div>
							</div>

							<?php if ($is_admin) { ?>
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left"></label>
									<div class="col-md-8">
										<div class="collapse" id="publish-date-collapse">
											<div class="row">
												<div class="col-md-5 col-lg-4">
													<div class="form-group">
														<input type="text" class="form-control datepicker-component" name="publish_date" id="publish_date" value="<?= date('d-m-Y', strtotime($form_value['publish_date'] ?? 'now')); ?>"/>
													</div>
												</div>
												<div class="col-md-4 col-lg-3">
													<div class="form-group">
														<select id="publish_time" name="publish_time" class="full-width select_withsearch">
															<?php for ($i = 0; $i < 23; $i++) { ?>
																<?php foreach (['00', '30'] as $minute): ?>
																	<?php $time = str_pad($i, 2, 0, STR_PAD_LEFT) . ':' . $minute ?>
																	<option <?php echo ($time == $form_value['publish_time'] ? 'selected' : '') ?>><?php echo $time ?></option>
																<?php endforeach; ?>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>

							<?php if ($is_admin): ?>
								<div class="form-group">
									<div class="row">
										<label class="col-md-3 control-label text-right sm-text-left">Featured on homepage</label>
										<div class="col-md-8">
											<div class="radio radio-complete">
												<input type="radio" value="1" name="featured_on_homepage" id="featured_on_homepage1" <?php echo ($form_value['featured_on_homepage'] === '1' ? 'checked="checked"' : ''); ?>>
												<label for="featured_on_homepage1">Yes</label>
												<input type="radio" value="0" name="featured_on_homepage" id="featured_on_homepage0" <?php echo ($form_value['featured_on_homepage'] === '0' ? 'checked="checked"' : ''); ?>>
												<label for="featured_on_homepage0">No</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group" style="display:none">
									<div class="row">
										<label class="col-md-3 control-label text-right sm-text-left">Trending</label>
										<div class="col-md-8">
											<div class="radio radio-complete">
												<input type="radio" value="1" name="trending" id="trending1" <?php echo ($form_value['trending'] === '1' ? 'checked="checked"' : ''); ?>>
												<label for="trending1">Yes</label>
												<input type="radio" value="0" name="trending" id="trending0" <?php echo ($form_value['trending'] === '0' ? 'checked="checked"' : ''); ?>>
												<label for="trending0">No</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group" style="display:none">
									<div class="row">
										<label class="col-md-3 control-label text-right sm-text-left">Recommend on homepage</label>
										<div class="col-md-8">
											<div class="radio radio-complete">
												<input type="radio" value="1" name="recommended" id="recommended1" <?php echo ($form_value['recommended'] === '1' ? 'checked="checked"' : ''); ?>>
												<label for="recommended1">Yes</label>
												<input type="radio" value="0" name="recommended" id="recommended0" <?php echo ($form_value['recommended'] === '0' ? 'checked="checked"' : ''); ?>>
												<label for="recommended0">No</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group" style="display:none"> <?php // echo ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>
									<div class="row">
										<label class="col-md-3 control-label text-right sm-text-left">Deletable</label>
										<div class="col-md-8 col-xs-12">
											<div class="radio radio-complete">
												<input type="radio" value="1" name="deletable" id="deletable1" <?php echo ($form_value['deletable'] === '1' ? 'checked="checked"' : ''); ?>>
												<label for="deletable1">Yes</label>
												<input type="radio" value="0" name="deletable" id="deletable0" <?php echo ($form_value['deletable'] === '0' ? 'checked="checked"' : ''); ?>>
												<label for="deletable0">No</label>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<div class="form-group">
								<div class="row"> <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
									<div class="col-md-8">
										<button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
										<input class="btn btn-complete sm-m-b-10 d-none" type="submit" name="submit_and_add_question" value="Submit & Add Question"/>
										<input class="btn btn-info sm-m-b-10" type="submit" name="preview" value="Preview"/>
										<button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
									</div>
								</div>
							</div>
						<?php echo form_close(); ?>
					</div>

					<?php if ($this->uri->segment(2) === 'edit'): ?>
						<?php $this->load->view('shared/quiz/questions') ?>
					<?php endif; ?>
				</div>
			</div>
			<!-- END CARD -->
		</div>
	</div>

	<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

<script type="text/javascript">
$(document).ready(function() {
	$('#content_form').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			title: {
				validators: {
					notEmpty: {
						message: 'Judul konten harus diisi. '
					}
				}
			},
			short_desc: {
				validators: {
					notEmpty: {
						message: 'Deskripsi singkat konten harus diisi. '
					}
				}
			},
			pic_caption: {
				validators: {
					notEmpty: {
						message: 'Caption gambar harus diisi. '
					}
				}
			},
			content: {
				excluded: false,
				validators: {
					notEmpty: {
						message: 'Konten konten harus diisi. '
					}
				}
			},
			file_pic: {
				validators: {
					notEmpty: {
						message: 'Pilih gambar konten. '
					}
				}
			},
			publish_date: {
				enabled: false,
				validators: {
					notEmpty: {
						message: 'Tanggal Publish harus diisi. '
					}
				}
			},
			publish_time: {
				enabled: false,
				validators: {
					notEmpty: {
						message: 'Waktu Publish harus diisi. '
					}
				}
			}
		}
	});
	$('input[type=radio][name=content_status]').change(function() {
		var value = $('input[type=radio][name=content_status]:checked').val()
		$('#publish-date-collapse').collapse(value  == 2 ? 'show' : 'hide')

		updateValidator();
	}).trigger('change');
	$('#content').ckeditor().editor.on('change', function() {
		$('#content_form').bootstrapValidator('revalidateField', 'content');
	});
});

function updateValidator(){
	var status = $('input[type=radio][name=content_status]:checked').val();
	if (status == '-1') {
		$("#short_desc").removeAttr("required");
		$("#pic_caption").removeAttr("required");
		$("#content").removeAttr("required");
		$("#file_pic").removeAttr("data-required");
		$("#file_pic").attr("data-required", "false");
		$("#file_pic").removeAttr("required");
		$("#file_pic").prop('required',false);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', false);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'pic_caption', false);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'content', false);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic', false);
	}
	else {
		var hasFilePreview = $('#file-pic-form-group .file-preview').length > 0;

		$("#short_desc").attr("required", "yes");
		$("#pic_caption").attr("required", "yes");
		$("#content").prop("required", true);
		$("#file_pic").attr("data-required", (!hasFilePreview).toString());
		$("#file_pic").prop('required', !hasFilePreview);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic', !hasFilePreview);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', true);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'pic_caption', true);
		$('#content_form').bootstrapValidator('enableFieldValidators', 'content', true);
	}
}
</script>
