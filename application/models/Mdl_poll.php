<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_poll extends Mdl_content2 {
	const TYPE = [3, 4];

	public function all($limit = NULL, $offset = NULL)
	{
		$vote_count_sql = "SELECT COUNT(tbl_content_poll_answer_vote.id) FROM `tbl_content_poll` JOIN `tbl_content_poll_answer` ON `tbl_content_poll`.`id`=`id_content_poll` JOIN `tbl_content_poll_answer_vote` ON `tbl_content_poll_answer`.`id`=`id_content_poll_answer`";

		$this->db->select("({$vote_count_sql}) AS vote_count");

		return parent::all($limit, $offset);
	}

	public function get_max_question_order_no($id_content)
	{
		return $this
			->db
			->select('COALESCE(MAX(order_no) + 1, 1) AS max_order_no')
			->where(compact('id_content'))
			->get('tbl_content_poll')
			->row()
			->max_order_no;
	}

	public function get_max_answer_order_no($id_content_poll)
	{
		return $this
			->db
			->select('COALESCE(MAX(order_no) + 1, 1) AS max_order_no')
			->where(compact('id_content_poll'))
			->get('tbl_content_poll_answer')
			->row()
			->max_order_no;
	}

	public function get_questions($id_content)
	{
		return $this
			->db
			->where(compact('id_content'))
			->order_by('order_no', 'asc')
			->get('tbl_content_poll')
			->result();
	}

	public function get_questions_with_answers($id_content, $order_no = NULL)
	{
		if ($order_no)
		{
			$this->db->where(compact('order_no'));
		}

		$polls = $this
			->db
			->where(compact('id_content'))
			->order_by('order_no', 'asc')
			->get('tbl_content_poll')
			->result();

		foreach ($polls as $poll)
		{
			$count_sql = 'COUNT(tbl_content_poll_answer_vote.id)';
			$total_vote_sql = $this
				->db
				->select('COUNT(*)')
				->join('tbl_content_poll_answer_vote', 'tbl_content_poll_answer.id=id_content_poll_answer')
				->where('id_content_poll', $poll->id)
				->get_compiled_select('tbl_content_poll_answer');

			if ($this->db->select('type')->get_where('tbl_content', compact('id_content'))->row()->type === '4')
			{
				$this->db->where('tbl_content_poll_answer.order_no <', 3);
			}

			$poll->answers = $this
				->db
				->select('tbl_content_poll_answer.id, tbl_content_poll_answer.answer, tbl_content_poll_answer.picture')
				->select("{$count_sql} AS counts")
				->select("{$count_sql} / ({$total_vote_sql}) * 100 AS percentage")
				->join(
					'tbl_content_poll_answer_vote',
					"tbl_content_poll_answer.id=tbl_content_poll_answer_vote.id_content_poll_answer",
					'left',
					TRUE
				)
				->group_by('tbl_content_poll_answer.id, tbl_content_poll_answer.answer, tbl_content_poll_answer.picture')
				->get_where('tbl_content_poll_answer', ['id_content_poll' => $poll->id])
				->result();
		}

		return $polls;
	}

	public function find_question($id, $with_answer = TRUE)
	{
		$question = $this
			->db
			->where(compact('id'))
			->get('tbl_content_poll')
			->row();

		if ($with_answer)
		{
			$question
				->answers = $this
				->db
				->where('id_content_poll', $question->id)
				->order_by('order_no', 'asc')
				->get('tbl_content_poll_answer')
				->result();
		}

		return $question;
	}

	public function find_answer($id)
	{
		return $this->db->where(compact('id'))->get('tbl_content_poll_answer')->row();
	}

	public function get_vote_of_user($id_content, $id_user)
	{
		$votes = $this
			->db
			->select('tbl_content_poll_answer_vote.*')
			->join('tbl_content_poll_answer', 'tbl_content_poll.id=tbl_content_poll_answer.id_content_poll', 'left')
			->join('tbl_content_poll_answer_vote', 'tbl_content_poll_answer.id=tbl_content_poll_answer_vote.id_content_poll_answer', 'left')
			->get_where('tbl_content_poll', [
				'tbl_content_poll.id_content' => $id_content,
				'tbl_content_poll_answer_vote.id_user' => $id_user
			])
			->result();

		$answer_ids = array_column($votes, 'id_content_poll_answer');

		return array_fill_keys($answer_ids, TRUE);
	}

	public function count_published_paginated($id_content = NULL)
	{
		$this->db->where('paginated', 1);
		return $this->count_published($id_content);
	}

	public function count_all_votes()
	{
		$count_sql = 'COUNT(tbl_content_poll_answer_vote.id)';

		$type = $this->db->select('type')->get_where('tbl_content', compact('id_content'))->row()->type;

		if ($type === '4')
		{
			$this->db->where('tbl_content_poll_answer.order_no <', 3);
		}

		$votes = $this
			->db
			->select('id_content')
			->select("{$count_sql} AS counts")
			->join('tbl_content_poll_answer', 'tbl_content_poll.id=tbl_content_poll_answer.id_content_poll', 'left')
			->join(
				'tbl_content_poll_answer_vote',
				"tbl_content_poll_answer.id=tbl_content_poll_answer_vote.id_content_poll_answer",
				'left',
				TRUE
			)
			->group_by('id_content')
			->get_where('tbl_content_poll')
			->result();

		$grouped_votes = [];
		$last_id_content = null;

		foreach ($votes as $key => $vote)
		{
			if ($key === 0 || $vote->id_content !== $last_id_content)
			{
				$grouped_votes[$vote->id_content] = [];
				$last_id_content = $vote->id_content;
			}

			$grouped_votes[$vote->id_content][] = $vote;
		}

		return $grouped_votes;
	}

	public function count_all_answer_votes($id_content)
	{
		$count_sql = 'COUNT(tbl_content_poll_answer_vote.id)';
		$total_vote_sql = "(SELECT COUNT(*) FROM tbl_content_poll_answer JOIN tbl_content_poll_answer_vote ON tbl_content_poll_answer.id=id_content_poll_answer WHERE id_content_poll=tbl_content_poll.id)";

		$type = $this->db->select('type')->get_where('tbl_content', compact('id_content'))->row()->type;

		if ($type === '4')
		{
			$this->db->where('tbl_content_poll_answer.order_no <', 3);
		}

		$votes = $this
			->db
			->select('id_content_poll, tbl_content_poll_answer.id')
			->select("{$count_sql} AS counts")
			->select("{$count_sql} / {$total_vote_sql} * 100 AS percentage")
			->join('tbl_content_poll_answer', 'tbl_content_poll.id=tbl_content_poll_answer.id_content_poll', 'left')
			->join(
				'tbl_content_poll_answer_vote',
				"tbl_content_poll_answer.id=tbl_content_poll_answer_vote.id_content_poll_answer",
				'left',
				TRUE
			)
			->group_by('id_content_poll, tbl_content_poll_answer.id')
			->get_where('tbl_content_poll', compact('id_content'))
			->result();

		$grouped_votes = [];
		$last_id_content_poll = null;

		foreach ($votes as $key => $vote)
		{
			if ($key === 0 || $vote->id_content_poll !== $last_id_content_poll)
			{
				$grouped_votes[$vote->id_content_poll] = [];
				$last_id_content_poll = $vote->id_content_poll;
			}

			$grouped_votes[$vote->id_content_poll][] = $vote;
		}

		return $grouped_votes;
	}

	public function count_all_questions($id_content)
	{
		return $this
			->db
			->where(compact('id_content'))
			->from('tbl_content_poll')
			->count_all_results();
	}

	public function add_votes($id_user, $votes)
	{
		if (count($votes) > 0)
		{
			$this->db->insert_batch('tbl_content_poll_answer_vote', array_map(function ($id_content_poll_answer) use ($id_user) {
				return compact('id_user', 'id_content_poll_answer');
			}, $votes));
		}
	}

	public function add_question($id_content, $data)
	{
		$data['id_content'] = $id_content;
		$this->db->insert('tbl_content_poll', $data);
	}

	public function add_answer($id_content_poll, $data)
	{
		$data['id_content_poll'] = $id_content_poll;
		$this->db->insert('tbl_content_poll_answer', $data);
	}

	public function update_question($id, $data)
	{
		$this->db->trans_start();

		$question = $this->db->select('id_content, order_no')->get_where('tbl_content_poll', compact('id'))->row();

		if (!empty($data['order_no']))
		{
			if ($data['order_no'] > $question->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no-1', FALSE)
					->where('id_content', $question->id_content)
					->where('order_no >', $question->order_no)
					->where('order_no <=', $data['order_no'])
					->update('tbl_content_poll');
			}
			elseif ($data['order_no'] < $question->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no+1', FALSE)
					->where('id_content', $question->id_content)
					->where('order_no >=', $data['order_no'])
					->where('order_no <', $question->order_no)
					->update('tbl_content_poll');
			}
		}

		$this->db->update('tbl_content_poll', $data, compact('id'));

		$this->db->trans_complete();
	}

	public function update_answer($id, $data)
	{
		$this->db->trans_start();

		$answer = $this
			->db
			->select('id_content_poll, order_no')
			->get_where('tbl_content_poll_answer', compact('id'))
			->row();

		if (!empty($data['order_no']))
		{
			if ($data['order_no'] > $answer->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no-1', FALSE)
					->where('id_content_poll', $answer->id_content_poll)
					->where('order_no >', $answer->order_no)
					->where('order_no <=', $data['order_no'])
					->update('tbl_content_poll_answer');
			}
			elseif ($data['order_no'] < $answer->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no+1', FALSE)
					->where('id_content_poll', $answer->id_content_poll)
					->where('order_no >=', $data['order_no'])
					->where('order_no <', $answer->order_no)
					->update('tbl_content_poll_answer');
			}
		}

		$this->db->update('tbl_content_poll_answer', $data, compact('id'));

		$this->db->trans_complete();
	}

	public function delete($id_content)
	{
		$this->db->trans_start();

		$id_content_poll = array_column(
			$this->db->select('id')->get_where('tbl_content_poll', compact('id_content'))->result(),
			'id'
		);

		$this->_delete_answer_of_question($id_content_poll);
		$this->db->delete('tbl_content_poll', compact('id_content'));

		parent::delete($id_content);

		$this->db->trans_complete();
	}

	public function delete_question($id)
	{
		$this->db->trans_start();

		$question = $this->db->select('id_content, order_no')->get_where('tbl_content_poll', compact('id'))->row();
		$this
			->db
			->set('order_no', 'order_no-1', FALSE)
			->where('id_content', $question->id_content)
			->where('order_no > ', $question->order_no)
			->update('tbl_content_poll');

		$this->_delete_answer_of_question($id);
		$this->db->delete('tbl_content_poll', compact('id'));

		$this->db->trans_complete();
	}

	public function delete_answer($id)
	{
		$this->db->trans_start();

		$answer = $this
			->db
			->select('id_content_poll, order_no')
			->get_where('tbl_content_poll_answer', compact('id'))
			->row();

		$this
			->db
			->set('order_no', 'order_no-1', FALSE)
			->where('id_content_poll', $answer->id_content_poll)
			->where('order_no > ', $answer->order_no)
			->update('tbl_content_poll_answer');

		$this->db->delete('tbl_content_poll_answer', compact('id'));
		$this->db->delete('tbl_content_poll_answer_vote', ['id_content_poll_answer' => $id]);
		$this->db->trans_complete();
	}

	private function _delete_answer_of_question($id_content_poll)
	{
		$id_content_poll = is_array($id_content_poll) ? $id_content_poll : [$id_content_poll];

		if (count($id_content_poll) > 0)
		{
			$id_content_poll_answer = array_column(
				$this->db->select('id')->where_in('id_content_poll', $id_content_poll)->get('tbl_content_poll_answer')->result(),
				'id'
			);

			if (count($id_content_poll_answer) > 0)
			{
				$this->db->where_in('id_content_poll_answer', $id_content_poll_answer)->delete('tbl_content_poll_answer_vote');
			}

			$this->db->where_in('id_content_poll', $id_content_poll)->delete('tbl_content_poll_answer');
		}
	}
}
