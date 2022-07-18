<?php $user_answer = $this->session->userdata('answers') ?: $user_answer ?>
<?php $is_answered = !empty($user_answer) && is_null($this->session->userdata('answers')) ?>

<div id="quiz" class="row">
  <div class="col">
    <div id="answer-info" class="text-center <?php echo ($is_answered ? '' : 'd-none') ?>">
        <div class="p-3">
          <h4>Jumlah jawaban anda yang benar</h4>
          <h3 class="m-b-0"><span id="total-correct-answers"><?php echo $total_correct_answer ?: 0 ?></span> dari <?php echo $total_question ?></h3>
        </div>
    </div>
  </div>
</div>

<div class="mt-20 text-center">
	<a id="reset-button" class="btn btn-default <?php echo (!$is_answered ? 'd-none' : '') ?>" href="<?php echo base_url() ?>content2/reset_quiz_answer/<?php echo $content->id_content ?>">
		Mulai Lagi
	</a>
</div>

<div class="mt-40">
	<?php if (!$is_answered): ?>
		<?php echo form_open(base_url() . "content2/answer/{$content->id_content}", ['id' => 'quiz-form']) ?>
	<?php endif; ?>

	<?php foreach ($questions as $index => $question): ?>
		<?php $question_percentage = ($index + 1) / $total_question * 100 ?>

		<div id="quiz-<?php echo $question->id ?>" class="quiz m-b-40">
			<div class="progress" style="height: 3px;">
				<div class="progress-bar" role="progressbar" style="width: <?php echo $question_percentage ?>%;" aria-valuenow="<?php echo $question_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
			</div>

			<small class="mb-5">Pertanyaan <?php echo $index + 1 ?> dari <?php echo $total_question ?></small>
			<h4 class="m-t-10"><?php echo $question->text ?></h4>

			<div class="card rounded-0 mb-20">
				<?php if (!empty($question->picture)): ?>
					<img class="card-img-top lazy" src="<?php echo $assets_url ?>/question/<?php echo $question->picture ?>">
				<?php endif; ?>

				<div class="card-body bg-light">
					<?php foreach ($question->choices as $inner_index => $choice): ?>
						<?php if ($question->type === '2'): ?>
							<?php if ($inner_index % 2 === 0): ?>
								<div class="row" style="margin-bottom: 0.75rem">
							<?php endif; ?>

							<div class="col-6" style="padding-left: 5px; padding-right: 5px;">
						<?php endif; ?>

						<div class="quiz-choice card <?php echo $is_answered ? 'quiz-choice-disabled' : ''  ?> <?php echo ($question->type === '1' ? 'quiz-choice-list' : '') ?>">
							<?php if (!empty($choice->picture)): ?>
								<img class="card-img-top lazy" src="<?php echo $assets_url ?>/choice/<?php echo $choice->picture ?>">
							<?php endif; ?>
							<div class="card-body p-0">
								<div style="line-height:20px;font-size:14px;" class="m-b-0">
									<div style="padding: .65rem;" class=" <?php echo $is_answered ? ($choice->is_answer ? 'bg-success-lighter' : (isset($user_answer[intval($choice->id)]) ? 'bg-danger-lighter' : '')) : '' ?>">
										<div class="custom-control custom-checkbox">
											<input type="radio" id="quiz-checkbox-<?php echo $choice->id ?>" name="answers[<?php echo $question->id ?>]" value="<?php echo $choice->id ?>" class="custom-control-input" <?php echo isset($user_answer[intval($choice->id)]) ? 'checked' : '' ?> <?php echo $is_answered ? 'disabled' : ''  ?>>
											<label class="custom-control-label" for="quiz-checkbox-<?php echo $choice->id ?>" style="word-break: break-word;"><?php echo $choice->text ?></label>
										</div>
									</div>
                </div>
							</div>
						</div>

						<?php if ($question->type === '2'): ?>
								</div> <!-- col -->
								<?php if ($inner_index % 2 === 1): ?>
									</div> <!-- row -->
								<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php if ($content->paginated === '0' || $page_no === $max_page_no): ?>
		<?php if (!$this->session->userdata('user_logged_in')): ?>
				<div class="text-center">
					<a class="btn btn-complete btn-block" href="<?php echo base_url() ?>page/login/<?php echo rtrim(base64_encode(urlencode($this->uri->uri_string())) , '=') ?>">Login untuk Menjawab Quiz</a>
				</div>
		<?php else: ?>
				<div class="text-center">
					<?php if (!$is_answered): ?>
						<?php if ($is_preview): ?>
							<button class="btn btn-complete btn-block" type="button">Lihat Hasil</button>
						<?php else: ?>
							<button id="answer-button" class="btn btn-complete btn-block" type="submit">Lihat Hasil</button>
						<?php endif; ?>
					<?php endif; ?>
				</div>
		</form>
		<?php endif; ?>
	<?php endif; ?>

	<?php if (!$is_answered): ?>
		<?php echo form_close()?>
	<?php endif; ?>
</div>
