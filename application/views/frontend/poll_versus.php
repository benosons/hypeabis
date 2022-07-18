	<?php if ($inner_index % 2 === 0): ?>
		<div class="row" style="margin-bottom: 0.75rem">
	<?php endif; ?>

	<div class="col-6" style="">
		<div class="poll-answer card" <?php echo (!empty($user_vote) ? 'style="display: none;"' : '') ?>>
			<?php if (!empty($answer->picture)): ?>
				<img class="card-img-top lazy" src="<?php echo base_url() ?>assets/poll/answers/<?php echo $answer->picture ?>" style="padding: 0.75rem 0.75rem 0;">
			<?php endif; ?>
			<div class="card-body p-0">
				<h5 class="mb-0">
					<div style="padding: .75rem;">
						<div class="custom-control custom-checkbox">
							<input type="radio" id="poll-checkbox-<?php echo $answer->id ?>" name="answers[<?php echo $question->id ?>]" value="<?php echo $answer->id ?>" class="custom-control-input">
							<label class="custom-control-label" for="poll-checkbox-<?php echo $answer->id ?>" style="word-break: break-word;"><?php echo $answer->answer ?></label>
						</div>
					</div>
				</h5>
			</div>
		</div>
		<div class="poll-answer poll-answer-disabled card" <?php echo (!empty($user_vote) ? '' : 'style="display: none;"') ?>>
			<?php if (!empty($answer->picture)): ?>
				<img class="card-img-top lazy" src="<?php echo base_url() ?>assets/poll/answers/<?php echo $answer->picture ?>">
			<?php endif; ?>
			<div class="card-body p-0 row">
				<?php if ($inner_index === 1): ?>
					<div class="poll-answer-result col-sm-4 pr-sm-0">
						<div class="h-100 <?php echo $question->answers[1]->counts < $question->answers[0]->counts ? 'bg-danger' : 'bg-success' ?>" style="padding: .75rem;">
							<h5 class="text-white mb-0">
								<span id="poll-answer-percentage-<?php echo $answer->id ?>"><?php echo rtrim(rtrim(number_format($answer->percentage, 1, ',', '.'), '0'), ',') ?></span>%
							</h5>
							<p class="text-white mb-0"><span id="poll-answer-count-<?php echo $answer->id ?>"><?php echo $answer->counts ?></span> votes</private>
						</div>
					</div>
				<?php endif; ?>
				<h4 class="poll-answer-label col-sm-8 <?php echo $inner_index === 0 ? 'pr-sm-0' : 'pl-sm-0' ?>">
					<div class="poll-answer-label-wrapper">
						<div class="custom-control custom-checkbox">
							<input type="radio" id="poll-checkbox-vote-<?php echo $answer->id ?>" name="vote[<?php echo $question->id ?>]" value="<?php echo $answer->id ?>" class="custom-control-input" <?php echo (isset($user_vote[$answer->id]) ? 'checked' : '') ?> disabled>
							<label class="custom-control-label" for="poll-checkbox-vote-<?php echo $answer->id ?>" style="word-break: break-word;"><?php echo $answer->answer ?></label>
						</div>
					</div>
				</h4>
				<?php if ($inner_index === 0): ?>
					<div class="poll-answer-result col-sm-4 pl-sm-0">
						<div class="h-100 <?php echo $question->answers[0]->counts < $question->answers[1]->counts ? 'bg-danger' : 'bg-success' ?>" style="padding: .75rem;">
							<h5 class="text-white mb-0">
								<span id="poll-answer-percentage-<?php echo $answer->id ?>"><?php echo rtrim(rtrim(number_format($answer->percentage, 1, ',', '.'), '0'), ',') ?></span>%
							</h5>
							<p class="text-white mb-0"><span id="poll-answer-count-<?php echo $answer->id ?>"><?php echo $answer->counts ?></span> votes</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

	</div> <!-- col -->
	<?php if ($inner_index % 2 === 1): ?>
		</div> <!-- row -->
	<?php endif; ?>
