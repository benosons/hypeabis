<?php $user_answers = $this->session->userdata('answers') ?: [] ?>
<?php if ($question->type === '2'): ?>
	<?php if ($inner_index % 2 === 0): ?>
		<div class="row" style="margin-bottom: 0.75rem;padding:0px 10px;">
	<?php endif; ?>
	<div class="col-6" style="padding-left: 5px; padding-right: 5px;">
<?php endif; ?>

	<div class="poll-answer card <?php echo ($question->type === '1' ? 'poll-answer-list' : '') ?>" <?php echo (!empty($user_vote) ? 'style="display: none;"' : '') ?>>
		<?php if (!empty($answer->picture)): ?>
			<img class="card-img-top lazy" src="<?php echo base_url() ?>assets/poll/answers/<?php echo $answer->picture ?>" style="padding: 0.75rem 0.75rem 0;">
		<?php endif; ?>
		<div class="card-body p-0">
      <div style="padding: .75rem;">
        <div class="custom-control custom-checkbox">
          <input type="radio" id="poll-checkbox-<?php echo $answer->id ?>" name="answers[<?php echo $question->id ?>]" value="<?php echo $answer->id ?>" class="custom-control-input" <?php echo isset($user_answers[$question->id]) && $answer->id === $user_answers[$question->id] ? 'checked' : '' ?>>
          <label class="custom-control-label" for="poll-checkbox-<?php echo $answer->id ?>" style="word-break: break-word;"><?php echo $answer->answer ?></label>
        </div>
      </div>
		</div>
	</div>

	<div class="poll-answer poll-answer-disabled card <?php echo ($inner_index == 0 ? 'border-top' : '') ?> <?php echo ($question->type === '1' ? 'poll-answer-list' : '') ?>" <?php echo (!empty($user_vote) ? '' : 'style="display: none;"') ?>>
		<?php if (!empty($answer->picture)): ?>
			<img class="card-img-top lazy" src="<?php echo base_url() ?>assets/poll/answers/<?php echo $answer->picture ?>">
		<?php endif; ?>
		<div class="card-body p-0 row">
			<div class="poll-answer-result col-sm-<?php echo $question->type === '1' ? '3' : '4' ?> pr-sm-0">
				<div class="h-100 text-white" style="background-color: #2d2c2b; padding: .75rem;">
					<p class="mb-0" style="line-height:14px;">
            <small>
              <b><span id="poll-answer-percentage-<?php echo $answer->id ?>"><?php echo rtrim(rtrim(number_format($answer->percentage, 1, ',', '.'), '0'), ',') ?></span>%</b>
              <br>
              <span id="poll-answer-count-<?php echo $answer->id ?>"><?php echo $answer->counts ?></span> votes
            </small>
          </p>
				</div>
			</div>
			<div class="poll-answer-label col-sm-<?php echo $question->type === '1' ? '6' : '8' ?> pl-sm-0">
				<div class="poll-answer-label-wrapper">
					<div class="progress">
						<div id="poll-answer-progress-<?php echo $answer->id ?>" class="progress-bar" role="progressbar" style="width: <?php echo $answer->percentage ?>%;" aria-valuenow="<?php echo $answer->percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="radio" id="poll-checkbox-vote-<?php echo $answer->id ?>" name="vote[<?php echo $question->id ?>]" value="<?php echo $answer->id ?>" class="custom-control-input" <?php echo (isset($user_vote[$answer->id]) || (isset($user_answers[$question->id]) && $answer->id === $user_answers[$question->id]) ? 'checked' : '') ?> disabled>
						<label class="custom-control-label" for="poll-checkbox-vote-<?php echo $answer->id ?>" style="word-break: break-word;"><?php echo $answer->answer ?></label>
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
