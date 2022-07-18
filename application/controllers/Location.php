<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
*
* @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
* @copyright	: Binari - 2020
* @copyright	: mail@binary-project.com
* @version		: Release: v1
* @link			  : www.binary-project.com
* @contact		: 0822 3709 9004
*
*/

class Location extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        //load library..

        //load model..
        $this->load->model('mdl_province');
        $this->load->model('mdl_city');
        $this->load->model('mdl_subdistrict');

        //construct script..

        //set page state
    }

    public function getProvince()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect('page/index');
        }

        //ambil data province..
        $data['status'] = 'success';
        $data['province_data'] = $this->mdl_province->getAllProvinceArray();

        echo json_encode($data);
    }

    public function getCityByIDProvince()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect('page/index');
        }

        $data['status'] = '';

        //ambil dan validasi id_province..
        $this->form_validation->set_rules('id_province', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['status'] = 'failed';
        }
        else {
            $id_province = $this->input->post('id_province');

            //cek id_province..
            $cek = $this->mdl_province->checkProvinceByID($id_province);
            if ($cek) {
                //ambil data city bedasarkan id province..
                $data['status'] = 'success';
                $data['city_data'] = $this->mdl_city->getCityByIDProvinceInArray($id_province);
            }
            else {
                $data['status'] = 'failed';
                $data['city_data'] = array();
            }
        }

        echo json_encode($data);
    }

    public function getSubdistrictByIDCity()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect('page/index');
        }

        $data['status'] = '';
        $data['message'] = '';

        //ambil dan validasi id_province..
        $this->form_validation->set_rules('id_city', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['status'] = 'failed';
        }
        else {
            $id_city = $this->input->post('id_city');

            //cek id_province..
            $cek = $this->mdl_city->checkCityByID($id_city);
            if ($cek) {
                //ambil data city bedasarkan id province..
                $data['status'] = 'success';
                $data['subdistrict_data'] = $this->mdl_subdistrict->getSubdistrictByIDCityInArray($id_city);
            }
            else {
                $data['status'] = 'failed';
                $data['subdistrict_data'] = array();
            }
        }

        echo json_encode($data);
    }
}