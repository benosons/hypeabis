<? $this->load->view('admin/verifiedmember/sub_header'); ?>

<div class="container-fluid container-fixed-lg content-wrapper">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-transparent">
				<div class="card-body">
					<h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
						Pendaftaran Verified Member
					</h4>

					<?= $this->session->flashdata('message'); ?>

					<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
						<?php echo form_open("admin_verifiedmember/saveEdit/{$submission->id}", ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'submission_form', 'autocomplete' => 'off']) ?>
							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Nama lengkap</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" value="<?php echo $submission->name ?>" readonly="yes">
									</div>
								</div>
							</div>

              <div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Email</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" value="<?php echo $submission->email ?>" readonly="yes">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Foto Profil</label>
									<div class="col-md-8 col-xs-12">
										<div class="file-preview">
											<div class="file-preview-thumbnails">
												<div class="file-preview-frame">
                          <a href="<?= base_url(); ?>assets/user/<?= $submission->picture ?>" target="_blank">
													  <img src="<?= base_url(); ?>assets/user/<?= $submission->picture ?>" class="img-thumbnail img-fluid" title="<?= $submission->picture ?>" width="auto" style="max-height:100px">
                          </a>
												</div>
											</div>
											<div class="clearfix"></div><div class="file-preview-status text-center text-success"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Foto Dokumen Identitas</label>
									<div class="col-md-8 col-xs-12">
										<div class="file-preview">
											<div class="file-preview-thumbnails">
												<div class="file-preview-frame">
                          <a href="<?= base_url(); ?>assets/user-ktp/<?= $submission->ktp_picture ?>" target="_blank">
													  <img src="<?= base_url(); ?>assets/user-ktp/<?= $submission->ktp_picture ?>" class="img-thumbnail img-fluid" title="<?= $submission->ktp_picture ?>" width="auto" style="max-height:200px">
                          </a>
												</div>
											</div>
											<div class="clearfix"></div><div class="file-preview-status text-center text-success"></div>
										</div>
									</div>
								</div>
							</div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left" for="contact_number">Nomor kontak:</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="contact_number" id="contact_number" readonly="yes" value="<?= (isset($submission->contact_number) ? $submission->contact_number : ''); ?>"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left" for="address">Alamat:</label>
                  <div class="col-md-8">
                    <textarea class="form-control" rows="5" name="address" readonly="yes"><?= (isset($submission->address) ? $submission->address : ''); ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Tempat / Tanggal Lahir:</label>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" readonly="yes" value="<?= (isset($submission->tempat_lahir) ? $submission->tempat_lahir : ''); ?>"/>
                  </div>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="dob" id="dob" readonly="yes" value="<?= (isset($submission->dob) ? date('d-m-Y', strtotime($submission->dob)) : ''); ?>"/>
                  </div>
                </div>
              </div>

              <div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Pekerjaan</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" value="<?php echo $submission->job_name; ?>" readonly="yes">
									</div>
								</div>
							</div>

              <div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Bidang</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" value="<?php echo $submission->job_field; ?>" readonly="yes">
									</div>
								</div>
							</div>

              <div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Interest</label>
									<div class="col-md-8 col-xs-12">
										<input class="form-control" type="text" value="<?php echo $submission->interest; ?>" readonly="yes">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Approval</label>
									<div class="col-md-8 col-xs-12">
										<div class="radio radio-complete">
											<input type="radio" value="1" name="is_accepted" id="status-accept" <?= ($form_value->is_accepted === '1' ? 'checked="checked"' : ''); ?>>
											<label for="status-accept">Accept</label>
											<input type="radio" value="0" name="is_accepted" id="status-reject" <?= ($form_value->is_accepted === '0' ? 'checked="checked"' : ''); ?>>
											<label for="status-reject">Reject</label>
										</div>
									</div>
								</div>
							</div>

							<div id="verified-reject-description-form-group" class="form-group" style="display: none;">
								<div class="row">
									<label class="col-md-3 control-label text-right sm-text-left">Reject Note</label>
									<div class="col-md-8 col-xs-12">
										<textarea class="form-control" name="reject_description" rows="4" <?= ($form_value->is_accepted === '0' ? 'readonly="yes"' : ''); ?>><?= $form_value->reject_description ?></textarea>
									</div>
								</div>
							</div>

              <? if(! ($form_value->is_accepted === '0')){ ?>
							<div class="form-group">
								<div class="row">
									<label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
									<div class="col-md-8">
										<button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
										<a class="btn btn-default sm-m-b-10" href="<?php echo base_url() ?>admin_verifiedmember"><i class="fa fa-chevron-circle-left"></i> Back</a>
									</div>
								</div>
							</div>
              <? } ?>
						<?php echo form_close() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var $submissionForm = $('#submission_form');

		$submissionForm.bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: '',
				invalid: 'fa fa-times',
				validating: 'fa-fa-refresh'
			},
			fields: {
				is_accepted: {
					validators: {
						notEmpty: {
							message: 'Response is required and cannot be empty'
						}
					}
				},
				reject_description: {
					validators: {
						notEmpty: {
							message: 'Reject Description is required and cannot be empty'
						}
					}
				}
			}
		});

		$('input[type=radio][name=is_accepted]').change(function() {
			var value = $('input[type=radio][name=is_accepted]:checked').val()
			console.log(value === '0')

			if (value === '1') {
				$('#verified-reject-description-form-group').slideUp();
			} else if (value === '0') {
				$('#verified-reject-description-form-group').slideDown();
			}

			$submissionForm.data('bootstrapValidator').resetForm();
			$submissionForm.bootstrapValidator('enableFieldValidators', 'reject_description', value === '0');
		}).trigger('change');
	});
</script>
