<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_quiz extends Mdl_content2 {
	const TYPE = 5;

	public function all($limit = NULL, $offset = NULL)
	{
		$vote_count_sql = "SELECT COUNT(tbl_content_quiz_answer.id) FROM `tbl_content_quiz_question` JOIN `tbl_content_quiz_choice` ON `tbl_content_quiz_question`.`id`=`id_content_quiz_question` JOIN `tbl_content_quiz_answer` ON `tbl_content_quiz_choice`.`id`=`id_content_quiz_choice`";

		$this->db->select("({$vote_count_sql}) AS vote_count");

		return parent::all($limit, $offset);
	}

	public function get_max_question_order_no($id_content)
	{
		return $this
			->db
			->select('COALESCE(MAX(order_no) + 1, 1) AS max_order_no')
			->where(compact('id_content'))
			->get('tbl_content_quiz_question')
			->row()
			->max_order_no;
	}

	public function get_max_choice_order_no($id_content_quiz_question)
	{
		return $this
			->db
			->select('COALESCE(MAX(order_no) + 1, 1) AS max_order_no')
			->where(compact('id_content_quiz_question'))
			->get('tbl_content_quiz_choice')
			->row()
			->max_order_no;
	}

	public function get_questions($id_content)
	{
		return $this
			->db
			->where(compact('id_content'))
			->order_by('order_no', 'asc')
			->get('tbl_content_quiz_question')
			->result();
	}

	public function get_questions_with_choices($id_content, $order_no = NULL)
	{
		if ($order_no)
		{
			$this->db->where(compact('order_no'));
		}

		$questions = $this
			->db
			->where(compact('id_content'))
			->order_by('order_no', 'asc')
			->get('tbl_content_quiz_question')
			->result();

		foreach ($questions as $question)
		{
			$count_sql = 'COUNT(tbl_content_quiz_answer.id)';
			$total_sql = $this
				->db
				->select('COUNT(*)')
				->join('tbl_content_quiz_answer', 'tbl_content_quiz_choice.id=id_content_quiz_choice')
				->where('id_content_quiz_question', $question->id)
				->get_compiled_select('tbl_content_quiz_choice');

			$columns = 'tbl_content_quiz_choice.id, tbl_content_quiz_choice.text, tbl_content_quiz_choice.picture, tbl_content_quiz_choice.is_answer';
			$question->choices = $this
				->db
				->select($columns)
				->select("{$count_sql} AS counts")
				->select("{$count_sql} / ({$total_sql}) * 100 AS percentage")
				->join(
					'tbl_content_quiz_answer',
					'tbl_content_quiz_choice.id=tbl_content_quiz_answer.id_content_quiz_choice',
					'left',
					TRUE
				)
				->group_by($columns)
				->get_where('tbl_content_quiz_choice', ['id_content_quiz_question' => $question->id])
				->result();
		}

		return $questions;
	}

	public function get_true_answers($id_content)
	{
		return $this
			->db
			->select('id_content_quiz_question, tbl_content_quiz_choice.id AS id_content_quiz_choice')
			->where(compact('id_content'))
			->where('is_answer', 1)
			->order_by('tbl_content_quiz_question.order_no', 'asc')
			->join('tbl_content_quiz_question', 'tbl_content_quiz_choice.id_content_quiz_question=tbl_content_quiz_question.id', 'left')
			->get('tbl_content_quiz_choice')
			->result();
	}

	public function find_question($id, $with_choice = TRUE)
	{
		$question = $this
			->db
			->where(compact('id'))
			->get('tbl_content_quiz_question')
			->row();

		if ($with_choice)
		{
			$question->choices = $this
				->db
				->where('id_content_quiz_question', $question->id)
				->order_by('order_no', 'asc')
				->get('tbl_content_quiz_choice')
				->result();
		}

		return $question;
	}

	public function find_choice($id)
	{
		return $this->db->where(compact('id'))->get('tbl_content_quiz_choice')->row();
	}

	public function get_answer_of_user($id_content, $id_user)
	{
		$answers = $this
			->db
			->select('tbl_content_quiz_answer.*, tbl_content_quiz_choice.is_answer')
			->join('tbl_content_quiz_choice', 'tbl_content_quiz_question.id=tbl_content_quiz_choice.id_content_quiz_question', 'left')
			->join('tbl_content_quiz_answer', 'tbl_content_quiz_choice.id=tbl_content_quiz_answer.id_content_quiz_choice', 'left')
			->get_where('tbl_content_quiz_question', [
				'tbl_content_quiz_question.id_content' => $id_content,
				'tbl_content_quiz_answer.id_user' => $id_user
			])
			->result();

		$answer_ids = array_column($answers, 'id_content_quiz_choice');
		$is_answers = array_column($answers, 'is_answer');

		return array_combine($answer_ids, $is_answers);
	}

	public function count_published_paginated($id_content = NULL)
	{
		$this->db->where('paginated', 1);
		return $this->count_published($id_content);
	}

	public function getTotalQuizActive()
	{
		$this->db->where('content_status', 1);
		$this->db->where('type', 5);
		return $this->db->get('tbl_content')->num_rows();
	}

	public function count_all_choice_answers($id_content)
	{
		$count_sql = 'COUNT(tbl_content_quiz_answer.id)';
		$total_sql = "(SELECT COUNT(*) FROM tbl_content_quiz_choice JOIN tbl_content_quiz_answer ON tbl_content_quiz_choice.id=id_content_quiz_choice WHERE id_content_quiz_question=tbl_content_quiz_question.id)";

		$answers = $this
			->db
			->select('id_content_quiz_question, tbl_content_quiz_choice.id')
			->select("{$count_sql} AS counts")
			->select("{$count_sql} / {$total_sql} * 100 AS percentage")
			->join('tbl_content_quiz_choice', 'tbl_content_quiz_question.id=tbl_content_quiz_choice.id_content_quiz_question', 'left')
			->join(
				'tbl_content_quiz_answer',
				"tbl_content_quiz_choice.id=tbl_content_quiz_answer.id_content_quiz_choice",
				'left',
				TRUE
			)
			->group_by('id_content_quiz_question, tbl_content_quiz_choice.id')
			->get_where('tbl_content_quiz_question', compact('id_content'))
			->result();

		$grouped_answers = [];
		$last_id_content_quiz_question = null;

		foreach ($answers as $key => $answer)
		{
			if ($key === 0 || $answer->id_content_quiz_question !== $last_id_content_quiz_question)
			{
				$grouped_answers[$answer->id_content_quiz_question] = [];
				$last_id_content_quiz_question = $answer->id_content_quiz_question;
			}

			$grouped_answers[$answer->id_content_quiz_question][] = $answer;
		}

		return $grouped_answers;
	}

	public function count_all_questions($id_content)
	{
		return $this
			->db
			->where(compact('id_content'))
			->from('tbl_content_quiz_question')
			->count_all_results();
	}

	public function count_correct_answer_of_user($id_content, $id_user)
	{
		return $this
			->db
			->select('tbl_content_quiz_answer.*, tbl_content_quiz_choice.is_answer')
			->join('tbl_content_quiz_choice', 'tbl_content_quiz_question.id=tbl_content_quiz_choice.id_content_quiz_question', 'left')
			->join('tbl_content_quiz_answer', 'tbl_content_quiz_choice.id=tbl_content_quiz_answer.id_content_quiz_choice', 'left')
			->where([
				'tbl_content_quiz_question.id_content' => $id_content,
				'tbl_content_quiz_answer.id_user' => $id_user,
				'is_answer' => 1,
			])
			->from('tbl_content_quiz_question')
			->count_all_results();
	}

	public function add_answers($id_user, $answers)
	{
		if (count($answers) > 0)
		{
			$this->db->insert_batch('tbl_content_quiz_answer', array_map(function ($id_content_quiz_choice) use ($id_user) {
				return compact('id_user', 'id_content_quiz_choice');
			}, $answers));
		}
	}

	public function add_question($id_content, $data)
	{
		$data['id_content'] = $id_content;
		$this->db->insert('tbl_content_quiz_question', $data);
	}

	public function add_choice($id_content_quiz_question, $data)
	{
		$this->db->trans_start();

		if ($data['is_answer'] === '1')
		{
			$this->db->update('tbl_content_quiz_choice', ['is_answer' => 0], compact('id_content_quiz_question'));
		}

		$data['id_content_quiz_question'] = $id_content_quiz_question;
		$this->db->insert('tbl_content_quiz_choice', $data);

		$this->db->trans_complete();
	}

	public function update_question($id, $data)
	{
		$this->db->trans_start();

		$question = $this->db->select('id_content, order_no')->get_where('tbl_content_quiz_question', compact('id'))->row();

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
					->update('tbl_content_quiz_question');
			}
			elseif ($data['order_no'] < $question->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no+1', FALSE)
					->where('id_content', $question->id_content)
					->where('order_no >=', $data['order_no'])
					->where('order_no <', $question->order_no)
					->update('tbl_content_quiz_question');
			}
		}

		$this->db->update('tbl_content_quiz_question', $data, compact('id'));

		$this->db->trans_complete();
	}

	public function update_choice($id, $data)
	{
		$this->db->trans_start();

		$choice = $this
			->db
			->select('id_content_quiz_question, order_no')
			->get_where('tbl_content_quiz_choice', compact('id'))
			->row();

		if (!empty($data['order_no']))
		{
			if ($data['order_no'] > $choice->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no-1', FALSE)
					->where('id_content_quiz_question', $choice->id_content_quiz_question)
					->where('order_no >', $choice->order_no)
					->where('order_no <=', $data['order_no'])
					->update('tbl_content_quiz_choice');
			}
			elseif ($data['order_no'] < $choice->order_no)
			{
				$this
					->db
					->set('order_no', 'order_no+1', FALSE)
					->where('id_content_quiz_question', $choice->id_content_quiz_question)
					->where('order_no >=', $data['order_no'])
					->where('order_no <', $choice->order_no)
					->update('tbl_content_quiz_choice');
			}
		}

		if (isset($data['is_answer']) && $data['is_answer'] === '1')
		{
			$this->db->update(
				'tbl_content_quiz_choice',
				['is_answer' => 0],
				['id_content_quiz_question' => $choice->id_content_quiz_question, 'is_answer' => 1]
			);
		}

		$this->db->update('tbl_content_quiz_choice', $data, compact('id'));


		$this->db->trans_complete();
	}

	public function delete($id_content)
	{
		$this->db->trans_start();

		$id_content_quiz_question = array_column(
			$this->db->select('id')->get_where('tbl_content_quiz_question', compact('id_content'))->result(),
			'id'
		);

		$this->_delete_choice_of_question($id_content_quiz_question);
		$this->db->delete('tbl_content_quiz_question', compact('id_content'));

		parent::delete($id_content);

		$this->db->trans_complete();
	}

	public function delete_question($id)
	{
		$this->db->trans_start();

		$question = $this->db->select('id_content, order_no')->get_where('tbl_content_quiz_question', compact('id'))->row();
		$this
			->db
			->set('order_no', 'order_no-1', FALSE)
			->where('id_content', $question->id_content)
			->where('order_no > ', $question->order_no)
			->update('tbl_content_quiz_question');

		$this->_delete_choice_of_question($id);
		$this->db->delete('tbl_content_quiz_question', compact('id'));

		$this->db->trans_complete();
	}

	public function delete_choice($id)
	{
		$this->db->trans_start();

		$choice = $this
			->db
			->select('id_content_quiz_question, order_no')
			->get_where('tbl_content_quiz_choice', compact('id'))
			->row();

		$this
			->db
			->set('order_no', 'order_no-1', FALSE)
			->where('id_content_quiz_question', $choice->id_content_quiz_question)
			->where('order_no > ', $choice->order_no)
			->update('tbl_content_quiz_choice');

		$this->db->delete('tbl_content_quiz_choice', compact('id'));
		$this->db->delete('tbl_content_quiz_answer', ['id_content_quiz_choice' => $id]);
		$this->db->trans_complete();
	}

	public function delete_answer_by_user($id_user)
	{
		$this->db->delete('tbl_content_quiz_answer', compact('id_user'));
	}

	private function _delete_choice_of_question($id_content_quiz_question)
	{
		$id_content_quiz_question = is_array($id_content_quiz_question) ? $id_content_quiz_question : [$id_content_quiz_question];

		if (count($id_content_quiz_question) > 0)
		{
			$id_content_quiz_choice = array_column(
				$this
					->db
					->select('id')
					->where_in('id_content_quiz_question', $id_content_quiz_question)
					->get('tbl_content_quiz_choice')
					->result(),
				'id'
			);

			if (count($id_content_quiz_choice) > 0)
			{
				$this->db->where_in('id_content_quiz_choice', $id_content_quiz_choice)->delete('tbl_content_quiz_answer');
			}

			$this->db->where_in('id_content_quiz_question', $id_content_quiz_question)->delete('tbl_content_quiz_choice');
		}
	}
}
