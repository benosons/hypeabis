<?php $this->load->view("shared/quiz/sub_header"); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
	<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<div class="row">
		<div class="col-md-12">

			<!-- START card -->
			<div class="card card-transparent">
				<div class="card-body">

					<h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
						<?php echo $heading_text ?> Item untuk <?php echo $content->title ?>
					</h4>

					<?php echo $this->session->flashdata('item_message'); ?>
					<? $content_value = $this->session->flashdata('content_value'); ?>

					<?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off']); ?>
						<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
							<div class="row">
								<div class="col-md-5">
									<?php if (($content->use_content_pic && $content->content_pic) || (!$content->use_content_pic && $content->shoppable_picture)): ?>
										<div class="shoppable-image-map">
											<img class="shoppable-image-image" src="<?php echo base_url(); ?>assets/shoppable/<?php echo $content->use_content_pic ? $content->content_pic : $content->shoppable_picture ?>" title="<?php echo $content->content_pic ?>" draggable="false">
											<div id="new-shoppable-image-pin" class="shoppable-image-pin" data-top="<?php echo $form_value['top_percentage'] ?>" data-left="<?php echo $form_value['left_percentage'] ?>" style="top: <?php echo $form_value['top_percentage'] ?>%; left: <?php echo $form_value['left_percentage'] ?>%;">
												<div class="shoppable-image-pin-icon"><?php echo $form_value['order_no'] ?></div>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<div class="col-md-7">
									<input type="hidden" name="top_percentage" value="<?php echo $form_value['top_percentage'] ?>" required/>
									<input type="hidden" name="left_percentage" value="<?php echo $form_value['left_percentage'] ?>" required/>

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
											<label class="col-md-2 control-label text-right sm-text-left">URL</label>
											<div class="col-md-10 col-xs-12">
												<input class="form-control" type="text" name="url" value="<?php echo $form_value['url'] ?>" required/>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<label class="col-md-2 control-label text-right sm-text-left">Nama</label>
											<div class="col-md-10 col-xs-12">
												<input class="form-control" type="text" name="name" value="<?php echo $form_value['name'] ?>" required/>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<label class="col-md-2 control-label text-right sm-text-left">Harga</label>
											<div class="col-md-10 col-xs-12">
												<div class="input-group">
													<div class="input-group-prepend transparent">
														<div class="input-group-text transparent">Rp. </div>
													</div>
													<input class="form-control text-right" type="number" name="price" value="<?php echo $form_value['price'] ?>" required/>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<label class="col-md-2 control-label text-right sm-text-left">Gambar</label>
											<div class="col-md-10 col-xs-12">
												<?php if (!empty($item->picture)): ?>
													<div class="file-preview">
														<a href="<?php echo $base_url ?>/delete_item_picture/<?php echo $item->id ?>" class="close fileinput-remove text-right" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
														<div class="file-preview-thumbnails align-items-baseline">
															<div class="file-preview-frame">
																<img src="<?php echo base_url(); ?>assets/shoppable/item/<?php echo $item->picture ?>" class="file-preview-image" title="<?php echo $item->picture ?>" width="auto" style="max-height:100px">
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
												<a class="btn btn-default sm-m-b-10" href="<?php echo $base_url ?>/edit/<?php echo $content->id_content ?>#items"><i class="fa fa-chevron-circle-left"></i> Back</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php echo form_close(); ?>
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
			text: {
				validators: {
					notEmpty: {
						message: 'Pertanyaan harus diisi. '
					}
				}
			},
		}
	});

	$('[name=order_no]').on('change', function () {
		$('#new-shoppable-image-pin .shoppable-image-pin-icon').text(this.value);
	})

	interact('#new-shoppable-image-pin')
		.draggable({
			inertia: true,
			autoScroll: true,
			modifiers: [
				interact.modifiers.restrictRect({
					restriction: 'parent',
					endOnly: true
				})
			],
			listeners: {
				move: dragMoveListener,
				end (event) {
					var target = event.target
					var parentWidth = target.parentNode.getBoundingClientRect().width
					var parentHeight = target.parentNode.getBoundingClientRect().height
					var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
					var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy
					var left = (parseFloat(target.getAttribute('data-left')) || 0) / 100 * parentWidth + x
					var top = (parseFloat(target.getAttribute('data-top')) || 0) / 100 * parentHeight + y

					document.querySelector('[name=left_percentage]').value = round(left / parentWidth * 100, 3)
					document.querySelector('[name=top_percentage]').value = round(top / parentHeight * 100, 3)
				}
			}
		});

	function dragMoveListener(event) {
		var target = event.target
		// keep the dragged position in the data-x/data-y attributes
		var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
		var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy

		/* console.log(x, y) */

		// translate the element
		target.style.webkitTransform =
			target.style.transform =
				'translate(' + x + 'px, ' + y + 'px)'

		// update the posiion attributes
		target.setAttribute('data-x', x)
		target.setAttribute('data-y', y)
	}

	function round(value, decimals) {
		return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
	}
});
</script>
