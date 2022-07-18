<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_verifiedmembersubmission extends CI_Model {
	public function all($limit = NULL, $offset = NULL)
	{
		return $this
			->db
			->select('tbl_verified_member_submission.*, name, email')
			->join('tbl_user', 'tbl_verified_member_submission.id_user=tbl_user.id_user')
			->order_by('tbl_verified_member_submission.created_at', 'desc')
			->get('tbl_verified_member_submission', $limit, $offset)
			->result();
	}

	public function find($id)
	{
		return $this
			->db
			->join('tbl_user', 'tbl_verified_member_submission.id_user=tbl_user.id_user', 'inner')
			->join('tbl_job', 'tbl_user.id_job=tbl_job.id_job', 'inner')
			->join('tbl_jobfield', 'tbl_user.id_jobfield=tbl_jobfield.id_jobfield', 'inner')
			->join('tbl_interest', 'tbl_user.id_interest=tbl_interest.id_interest', 'inner')
			->order_by('tbl_verified_member_submission.created_at', 'asc')
			->get_where('tbl_verified_member_submission', compact('id'))
			->row();
  }
  
	public function latest($id_user)
	{
		return $this
			->db
			->order_by('created_at', 'desc')
			->limit(1)
			->get_where('tbl_verified_member_submission', compact('id_user'))
			->row();
	}

	public function update($data, $id)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');

		$this->db->where(compact('id'))->update('tbl_verified_member_submission', $data);
	}

	public function count()
	{
		return $this->db->from('tbl_verified_member_submission')->count_all_results();
	}
	
	public function count_requires_approval()
	{
		$this->db->where('is_accepted', NULL);

		return $this->count();
	}

	public function delete($id)
	{
		$this->db->delete('tbl_verified_member_submission', compact('id'));
	}
}
