<div id="poll" class="mt-40">
	<?php if (empty($user_vote)): ?>
		<?php echo form_open(base_url() . "content2/vote/{$content->id_content}", ['id' => 'polls-form']) ?>
	<?php endif; ?>
	
	<?php foreach ($questions as $index => $question): ?>
		<?php $question_percentage = ($index + $page_no) / $total_question * 100 ?>

		<div id="poll-<?php echo $question->id ?>" class="poll <?php echo $content->type === '4' ? 'poll-versus' : ''?> m-b-40">
			<div class="progress w-100" style="height: 3px;">
				<div class="progress-bar" role="progressbar" style="width: <?php echo $question_percentage ?>%;" aria-valuenow="<?php echo $question_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
			</div>

			<small>Pertanyaan <?php echo $index + $page_no ?> dari <?php echo $total_question ?></small>
			<h4 class="m-t-10"><?php echo $question->question ?></h4>

			<div class="rounded-0 mb-20 b-0">
				<?php if (!empty($question->picture)): ?>
					<img class="card-img-top lazy mb-2" src="<?php echo base_url() ?>assets/poll/questions/<?php echo $question->picture ?>" alt="Card image cap">
				<?php endif; ?>

				<div class="b-0 p-0">
					<?php foreach ($question->answers as $inner_index => $answer): ?>
						<?php $data = compact('answer', 'inner_index', 'question') ?>
						<?php if ($content->type === '3'): ?>
							<?php $this->load->view('frontend/poll_classic', $data); ?>
						<?php else: ?>
							<?php $this->load->view('frontend/poll_versus', $data); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	
	<?php if ($content->paginated === '0' || $page_no === $max_page_no): ?>
		<?php if (!$this->session->userdata('user_logged_in')): ?>
				<div class="text-center mb-5">
					<?php if ($is_preview): ?>
						<a class="btn btn-default btn-lg">Login to Vote</a>
					<?php else: ?>
						<a class="btn btn-default btn-lg" href="<?php echo base_url() ?>page/login/<?php echo rtrim(base64_encode(urlencode($this->uri->uri_string())) , '=') ?>">Login to Vote</a>
					<?php endif; ?>
				</div>
		<?php elseif (empty($user_vote)): ?>
				<div class="text-center">
					<?php if ($is_preview): ?>
						<button class="btn btn-default btn-lg" type="button">Vote</button>
					<?php else: ?>
						<button id="vote-button btn-lg" class="btn btn-default" type="submit">Vote</button>
					<?php endif; ?>
				</div>
		</form>
		<?php endif; ?>
	<?php endif; ?>
</div>
