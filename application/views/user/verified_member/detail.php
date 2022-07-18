<? $this->load->view('user/verified_member/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
	<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">

					<?= $this->session->flashdata('message'); ?>

					<div class="row">
						<div class="col-12">
							<h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Dokumen Pengajuan</h4>
              <p>*Semua form wajib diisi.</p>

							<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
								<?= form_open_multipart('user_verified_member/submit', ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'user_form', 'autocomplete' => 'off']); ?>
                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left" for="name">Nama Lengkap:</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="name" id="name" value="<?= (isset($user->name) ? $user->name : ''); ?>" required />
                        <p class="help-block">*Nama harus sesuai dengan dokumen identitas.</p>
											</div>
										</div>
									</div>

									<?php if (empty($user->picture)): ?>
										<div class="form-group">
											<div class="row">
												<label class="col-md-3 control-label text-right sm-text-left">Foto profil:</label>
												<div class="col-md-9">
													<input type="file" class="file" name="picture" id="picture" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
													<p class="hint-text"><small>*(Size Recommendation: <?= $this->user_pic_width; ?>px x <?= $this->user_pic_height; ?>px.)</small></p>
												</div>
											</div>
										</div>
                  <?php endif; ?>

									<div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left" for="ktp-picture">Foto Dokumen Identitas:</label>
											<div class="col-md-9">
                        <input type="file" class="file" name="ktp_picture" id="ktp-picture" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]' />
                        <p class="help-block">*Upload foto selfie kamu sambil memegang kartu identitas (KTP/SIM/Paspor).</p>
											</div>
										</div>
                  </div>
                  
                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left" for="contact_number">Nomor kontak:</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="contact_number" id="contact_number" value="<?= (isset($user->contact_number) ? $user->contact_number : ''); ?>" required />
                        <p class="help-block">*Masukkan nomor kontak yang dapat dihubungi. Nomor kontak kamu tidak akan disebarluaskan.</p>
											</div>
										</div>
                  </div>
                  
                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left" for="address">Alamat:</label>
											<div class="col-md-9">
												<textarea class="form-control" rows="5" name="address" required><?= (isset($user->address) ? $user->address : ''); ?></textarea>
											</div>
										</div>
                  </div>
                  
                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left">Tempat / Tanggal Lahir:</label>
											<div class="col-md-4 col-lg-3">
												<input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="<?= (isset($user->tempat_lahir) ? $user->tempat_lahir : ''); ?>" required />
                      </div>
                      <div class="col-md-4 col-lg-3">
												<input type="text" class="form-control datepicker-component" name="dob" id="dob" value="<?= (isset($user->dob) ? date('d-m-Y', strtotime($user->dob)) : ''); ?>" required />
											</div>
										</div>
                  </div>

                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left">Pekerjaan:</label>
											<div class="col-md-6">
                        <select name="id_job" class="full-width select_withsearch" required>
                          <option value="">- Pilih pekerjaan -</option>
                          <? foreach($job as $item){ ?>
                            <option value="<?= $item->id_job; ?>" <?= ($item->id_job == $user->id_job ? 'selected' : ''); ?>>
                              <?= $item->job_name; ?>
                            </option>
                          <? } ?>
                        </select>
                      </div>
										</div>
                  </div>

                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left">Bidang:</label>
											<div class="col-md-6">
                        <select name="id_jobfield" class="full-width select_withsearch" required>
                          <option value="">- Pilih bidang -</option>
                          <? foreach($jobfield as $item){ ?>
                            <option value="<?= $item->id_jobfield; ?>" <?= ($item->id_jobfield == $user->id_jobfield ? 'selected' : ''); ?>>
                              <?= $item->job_field; ?>
                            </option>
                          <? } ?>
                        </select>
                      </div>
										</div>
                  </div>

                  <div class="form-group">
										<div class="row">
											<label class="col-md-3 control-label text-right sm-text-left">Interest:</label>
											<div class="col-md-6">
                        <select name="id_interest" class="full-width select_withsearch" required>
                          <option value="">- Pilih interest -</option>
                          <? foreach($interest as $item){ ?>
                            <option value="<?= $item->id_interest; ?>" <?= ($item->id_interest == $user->id_interest ? 'selected' : ''); ?>>
                              <?= $item->interest; ?>
                            </option>
                          <? } ?>
                        </select>
                      </div>
										</div>
                  </div>

									<div class="form-group">
										<div class="row">
											<label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
											<div class="col-md-9">
												<button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
												<button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Kembali</button>
											</div>
										</div>
									</div>
								<?= form_close(); ?>
							</div>
						</div>
					</div>
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
	$('#user_form').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			name: {
				group: '.col-md-9',
				validators: {
					notEmpty: {
						message: 'Name is required and cannot be empty'
					}
				}
			},
			contact_number: {
				group: '.col-md-9',
				validators: {
					notEmpty: {
						message: 'Contact number is required and cannot be empty'
					}
				}
			}
		}
	});
});
</script>
