<?php $this->load->view("shared/polling/sub_header"); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
	<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">

					<h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
						<?php echo $heading_text ?> Jawaban untuk <?php echo $question->question ?>
					</h4>

					<?php echo $this->session->flashdata('answer_message'); ?>
					<? $content_value = $this->session->flashdata('content_value'); ?>

					<?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off']); ?>
						<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
							<div class="form-group">
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">No</label>
									<div class="col-md-10 col-xs-12">
										<input class="form-control" type="number" name="order_no" min="1" max="<?php echo $max_order_no ?>" value="<?php echo $form_value['order_no'] ?>" required/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">Jawaban</label>
									<div class="col-md-10 col-xs-12">
										<input class="form-control" type="text" name="answer" value="<?php echo $form_value['answer'] ?>" required/>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-2 control-label text-right sm-text-left">Gambar Jawaban</label>
									<div class="col-md-10 col-xs-12">
										<?php if (!empty($answer->picture)): ?>
											<div class="file-preview">
												<a href="<?php echo $base_url ?>/delete_answer_picture/<?php echo $answer->id ?>" class="close fileinput-remove text-right" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
												<div class="file-preview-thumbnails align-items-baseline">
													<div class="file-preview-frame">
														<img src="<?php echo base_url(); ?>assets/poll/answers/<?php echo $answer->picture ?>" class="file-preview-image" title="<?php echo $answer->picture ?>" width="auto" style="max-height:100px">
													</div>
												</div>
												<div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
											</div>
										<?php endif; ?>
										<input type="file" class="file" name="picture" id="picture" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
										<p class="hint-text"><small>*(Maksimal 5MB. Format file yang diperbolehkan: jpg, png &amp; gif.)</small></p>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
									<div class="col-md-8">
										<button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
										<a class="btn btn-default sm-m-b-10" href="<?php echo $base_url ?>/edit_question/<?php echo $question->id ?>#answers"><i class="fa fa-chevron-circle-left"></i> Back</a>
									</div>
								</div>
							</div>
						</div>
					<?php echo form_close(); ?>

					<?php if ($this->uri->segment(2) === 'edit_question'): ?>
						<?php $this->load->view('shared/polling/answers') ?>
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
			order_no: {
				validators: {
					notEmpty: {
						message: 'Nomor harus diisi.'
					}
				}
			},
			answer: {
				validators: {
					notEmpty: {
						message: 'Jawaban harus diisi. '
					}
				}
			},
		}
	});
});
</script>
